# WP Custom User Email
Quick and easy customization plugin for Wordpress Welcome new user email.

## Description

This plugin allows you to customize the welcome registration email with a custom message.
Quickly edit your new user welcome notification. No configuration needed, just change the subject the email text using all useful available shortcodes.
Send to new users their login info and the *activation link to set the password.*

You can change and customize your subject, your *welcome email* text using some shortcodes.

## Shortcodes
*[[site_url]] [[username]] [[first_name]] [[last_name]] [[password_url]]*

## Getting Started

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Customize your welcome email with the Tinymce html editor
4. Add some css or create a custom template if needed

## Example new user registration template

```
<h1>Hi, [[first_name]]!</h1>
Welcome to our  <strong>[[site_url]].</strong>
You are almost ready to complete your registration, one more step needed.
<strong>Your Account</strong>

Username: <strong>[[username]]</strong>
<a href="[[password_url]]">Activate your account</a>
[[password_url]]

Greetings
<strong>[[site_url]]</strong>
```


Here's a link to [My blog](https://www.giuseppesurace.com "My blog") where you can find more informations about.
