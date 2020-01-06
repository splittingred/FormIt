[![Contributors](https://img.shields.io/github/contributors/Sterc/FormIt.svg?style=flat-square)](https://github.com/Sterc/FormIt/graphs/contributors)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0)

# FormIt for MODX

FormIt is a dynamic form processing snippet for MODX Revolution developers. It handles form submission, validation and followup actions like sending an email or storing encrypted versions of these mails for later reference.

Screenshots and more information can be found on the [Sterc website](https://www.sterc.com/modx/extras/formit).

## Upgrading to FormIt 3.0.0? It involves a migration

We've released FormIt 3.0 in order to avoid issues with Mcrypt in the near future, since it will be [deprecated in PHP 7.2](http://php.net/manual/en/migration71.deprecated.php). Mcrypt has therefore been replaced by OpenSSL encryption.

After updating to 3.0.0, you should refresh the page and notice a red bar on the top of your manager. From there, you can initiate the migration process, which will unencrypt your saved forms using Mcrypt and immediately encrypt it using OpenSSL.

## Free Extra

This is a free extra and the code is publicly available for you to change. The extra is being actively maintained and you're free to put in pull requests which match our roadmap. Please create an issue if the pull request differs from the roadmap so we can make sure we're on the same page.

Need help? [Approach our support desk for paid premium support](mailto:service@sterc.com).
