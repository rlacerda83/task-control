# Mobly - Tasks Manager

## Description
Manages and automatically sends the tasks to Google!

## Current Features
- Interface web
- Task Manager (insert, edit and remove)
- Send tasks manually
- Send tasks automatically using cron
- Send tasks automatically using supervisord
- Send tasks using cli commands

## Upcoming Features
- Implement cache
- Send tasks using parallel requests

## Setup

#### Installation

- Clone the repository
- Rename the .env.example file to .env and edit the file by putting your settings
- Go to the project folder and run in the terminal: `php artisan install`
- Configure the web server. If you use nginx copying the contents of the nginx file in the project root.

#### Task process configuration
To synchronize automatically tasks you need to enable automatic synchronization in the settings menu.

Tasks can be synchronized in the following ways:
- Manually in the Tasks menu
- Automatically using cron. To use this option simply copy the contents of the cron file in the project root and create a cron entry on your machine.
- Automatically using supervisor. To use this option to copy the contents of the file supervisor in the project root and add the configuration to your supervisor.
- Manually running on the terminal: `php artisan queue-listen` or `php artisan queue-work`. This option only works if automatic synchronization is enabled
- Manually running `php artisan tasks:process`

## Contributing
Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security
If you discover any security related issues, please email r.lacerda83@gmail.com instead of using the issue tracker.