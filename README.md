# Citizen payment by ZingyBits - CitizenCore UI module

    ``zingybits/module-citizencore``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
ZingyBits_CitizenCore module

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/ZingyBits`
 - Enable the module by running `php bin/magento module:enable ZingyBits_CitizenCore`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Install the module composer by running `composer require zingybits/module-citizencore`
 - Use it to update packages `composer update`
 - enable the module by running `php bin/magento module:enable ZingyBits_CitizenCore`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

 - citizen - payment/citizen/*


## Specifications

 - Block
	- ModalPopup > modalpopup.phtml

 - Payment Method
	- citizen


## Attributes



