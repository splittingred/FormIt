<?php
/**
 * FormIt
 *
 * Copyright 2009-2012 by Shaun McCormick <shaun@modx.com>
 *
 * FormIt is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * FormIt is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormIt; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit
 */
/**
 * reCaptcha modX service class.
 *
 * Based off of recaptchalib.php by Mike Crawford and Ben Maurer. Changes include converting to OOP and making a class.
 *
 * @package formit
 * @subpackage recaptcha
 */
require_once dirname(dirname(__DIR__)) . '/src/FormIt/Service/RecaptchaService.php';

class FormItReCaptcha extends Sterc\FormIt\Service\RecaptchaService
{

}

/**
 * A reCaptchaResponse is returned from reCaptcha::check_answer()
 *
 * @package formit
 * @subpackage recaptcha
 */
require_once dirname(dirname(__DIR__)) . '/src/FormIt/Service/RecaptchaResponse.php';

class FormItReCaptchaResponse extends Sterc\FormIt\Service\RecaptchaResponse
{

}
