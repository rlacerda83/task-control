<?php

namespace App\Http\Controllers;

use App\Helpers\UserLogged;
use App\Models\Configuration;
use App\Repository\ConfigurationRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class ConfigurationController extends BaseController
{
    /**
     * @var ConfigurationRepository
     */
    private $repository;

    /**
     * @param ConfigurationRepository $repository
     */
    public function __construct(ConfigurationRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
     */
    public function showAction(Request $request)
    {
        $configuration = $this->repository->findFirst();
        if (!$configuration) {
            $data = ['send_email_process' => '1'];
            $data['url_form'] = Configuration::URL_FORM;
            $data['user_id'] = UserLogged::getId();
            $configuration = Configuration::firstOrCreate($data);
        }

        return view('configuration.form', [
            'configuration' => $configuration,
            'password' => strlen($configuration->password) ? Crypt::decrypt($configuration->password) : $configuration->password,
            'choices' => Configuration::$selectChoices
        ]);
    }

    public function saveAction(Request $request)
    {
        $params = $request->all();
        unset($params['_token'], $params['q']);

        if (strlen($params['password'])) {
            $params['password'] = Crypt::encrypt($params['password']);
        }

        if ($request->getMethod() == 'POST') {

            // saving data!
            $isValid = $this->repository->validateRequest($request);

            if (!is_bool($isValid)) {
                $request->session()->flash('message', "Invalid data, please check the following errors: ");
                $request->session()->flash('validationErrros', $isValid);

                return redirect()->route('configuration')->withInput();
            }

            $configuration = $this->repository->findById($params['id']);

            if (!$configuration) {
                $request->session()->flash('message', "Configuration not found");
                return redirect('configuration');
            }

            $this->repository->update($params, $params['id']);
            $request->session()->flash('message', "Configuration updated successfully!");
            $request->session()->flash('success', true);
            return redirect('configuration');
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect('configuration');
    }

}
