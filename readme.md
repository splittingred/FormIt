# FormIt for MODX
FormIt is a dynamic form processing snippet for MODX Revolution developers. It handles form submission, validation and followup actions like sending an email or storing encrypted versions of these mails for later reference.

Screenshots and more information can be found on the [Sterc website](https://www.sterc.nl/en/modx-extras/formit-3.0).

# Upgrading to FormIt 3.0.0? It involves a migration!
We've released FormIt 3.0 in order to avoid issues with Mcrypt in the near future, since it will be [deprecated in PHP 7.2](http://php.net/manual/en/migration71.deprecated.php). Mcrypt has therefore been replaced by OpenSSL encryption.

After updating to 3.0.0, you should refresh the page and notice a red bar on the top of your manager. From there, you can initiate the migration process, which will unencrypt your saved forms using Mcrypt and immediately encrypt it using OpenSSL. 

**It is highly recommended to migrate ASAP!**
