### Youtrack To Wunderlist Integration
Syncs all open tickets on Youtrack assigned to you, on to Wunderlist.

### How to use this?

To clone the repostiory, on the commandline
```
git clone https://github.com/SamihSoylu/YoutrackToWunderlistIntegration.git .
```
Then install all dependencies with composer
```
composer install
```

Duplicate `.env.example` to `.env` and fill in your credentials. Perhaps the most confusing part is the `WL_LIST` variable, this is the name of which Wunderlist **list**; **new** YouTrack tickets will sync to. Go on to your Wunderlist account, and right click any list, and then choose rename. Copy the name and paste it in to this WL_LIST variable field.

Set an hourly cronjob that will execute sync.php.

### Set-up a cronjob
On the command line to set up a cronjob, execute:
```
crontab -e
```

And an example hourly cronjob. Don't forget to update the path to the project.
```
0 */1 * * * php /path/to/YoutrackToWunderlistIntegration/sync.php
```
