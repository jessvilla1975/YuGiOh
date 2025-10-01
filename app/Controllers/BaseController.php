<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;



class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	//protected $helpers = [];
	protected $helpers = ['form', 'text', 'download', 'file', 'Curl', 'icon_helper'];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);


		$this->session = \Config\Services::session();
	}



    /**
     * 
     *                          SUCCESS & ERRORS
     * --------------------------------------------------------------------
     * This function for configure the flash data with default HTML tag.
     * Can make easier to get the message with $('.class').html() on JQuery.
     * You can using all HTML tag you want, just setting the $data with your own.
     * --------------------------------------------------------------------
     * 
     */
    protected function success($title, $paragraph = null)
    {
        $data = '<div class="success">' . $title . '<p class="text-muted">' . $paragraph . '</p></div>';
        return $data;
    }
    protected function errors($title, $paragraph = null)
    {
        $data = '<div class="errors">' . $title . '<p class="text-muted">' . $paragraph . '</p></div>';
        return $data;
    }
}
