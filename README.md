# SecureBin
**SecureBin** is an encrypted pastebin where the server has got zero knowledge of the paste data.

#### Official website https://securebin.ml/
---

## Requirements
#### MySQL
* PDO
#### PHP 7.2.0+

## What SecureBin provides
* Encryption of pasted data
* A wide range of settings and themes

## Options
* Password protection
* Paste self destruction

## Advantages
* In case of a data breach the pasted data is encrypted and safe.
* No data can be traced back to the paste creator without the decryption key.
* Several different themes
* ProjectHoneypot integration
* Ability to blacklist weak encryption keys
* Ability to prevent crawlers from increasing the views, eg. DiscordBot & TwitterBot

## Coming soon
* Burn paste if incorrect password has been provided
* Browser Integrity Check
#### Evaluate headers from your visitors browser for threats.

## What to look out for
Since site administrators has got full access to the source, they could start logging, or inject malicious code at any time. So you have to trust the site administrator with your pasted data.
