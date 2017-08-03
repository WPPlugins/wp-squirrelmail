<?php
/*
 * Copyright (C) 2016 Edgar Hernandez
 *
 * WP-SquirrelMail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Create Custom Login Form for SquirrelMail.
 *
 * @author Edgar Hernandez
 */
class WP_Squirrelmail_Login_Form {
    /**
     * @var string Form Action attribute
     */
    protected $action;
    
    /**
     * @var string SquirrelMail Username 
     */
    protected $username;
    
    /**
     * @var string SquirrelMail Password 
     */
    protected $password;
    
    /**
     * @var integer 0 = off, 1 = on 
     */
    protected $autologin;
    
    /**
     * Create a Custom Login form for SquirrelMail.
     * The form can be summited automatically by turning autologin on.
     * username and password are required for autologin.
     * 
     * @param string $action Form action attribute, e.g. "/webmail/src/redirect.php"
     * @param string $username SquirrelMail Username 
     * @param string $password SquirrelMail Password 
     * @param integer $autologin Auto login is off by default, e.g. "0 = off, 1 = on"
     */
    public function __construct($action = null, $username = null, $password = null, $autologin = 0) {
        $this->setAction($action);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setAutoLogin($autologin);
    }

    /**
     * Example: "/webmail/src/redirect.php"
     * @param string $action Form action attribute.
     */
    public function setAction($action) {
        $this->action = $action;
    }
    
    /**
     * @return string Return form action attribute
     */
    public function getAction() {
        return $this->action;
    }
    
    /**
     * Set SquirrelMail Username 
     * @param string $username SquirrelMail Username 
     */
    public function setUsername($username) {
        $this->username = $username;
    }
    
    /**
     * Get SquirrelMail Username
     * @return string SquirrelMail Username 
     */
    public function getUsername(){
        return $this->username;
    }
    
    /**
     * Set SquirrelMail Password
     * @param string $password SquirrelMail Password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
    
    /**
     * Get SquirrelMail Password
     * @return type SquirrelMail Password
     */
    protected function getPassword(){
        return $this->password;
    }
    
    /**
     * Default off; e.g. "0 = off, 1 = on"
     * @param integer $autologin Auto Login
     */
    public function setAutoLogin($autologin) {
        $this->autologin = (int) $autologin;
    }
    
    /**
     * Return Auto Login state
     * @return integer Auto Login
     */
    public function getAutoLogin() {
        return $this->autologin;
    }
    
    /**
     * Hide Login Form
     * @return string css style
     */
    protected function hideForm() {
        if($this->getAutoLogin() !== 1) {
            return;
        }
        $style = 'style = "display : none"';
        
        return $style;
    }

    /**
     * 
     * @return string HTML Form
     * @throws \Exception
     */
    public function getForm(){
        if($this->getAction() == null){
            /**
             * Use the function setAction() to set the form action attribute
             * Or you can instanciate the class with the attribute, e.g. \"new LogInForm(\$action);\"
             */
            throw new \Exception("<h2>" . WPSQUIRRELMAIL__NAME . " " . WPSQUIRRELMAIL__VERSION . "</h2>"
                    . "<p>" . _e( 'Please contact your system administrator', 'wp-squirrelmail' ) . "</p>" );
        }
        $form = '<div class="panel panel-default col-md-offset-4 col-md-4">';
        $form.= '<div class="panel-heading">';
        $form.= '<h2 class="panel-title">' . WPSQUIRRELMAIL__NAME . '</h2>';
        $form.= '</div>';
        $form.= '<div class="panel-body">';
        $form.= '<form method="post" action="/' . $this->getAction() . '" class="wp-squirrelmail-form" id="wp-squirrelmail-form" ' . $this->hideForm() . ">\n";
        $form.= '<input type="hidden" name="js_autodetect_results" value="0">' . "\n";
        $form.= '<input type="hidden" name="just_logged_in" value="1">' . "\n";
        
        $form.= '<div class="form-group">';
        $form.= '<label for="login_username">' . __('Email', 'wp-squirrelmail') . '</label>';
        $form.= '<input type="text" id="username" class="form-control" name="login_username" '
                . 'required="required" value="' . $this->getUsername() . '"'
                . 'placeholder="' . __('User Name', 'wp-squirrelmail') . '" autofocus="autofocus"><br />' . "\n";
        $form.= '</div>';
        
        $form.= '<div class="form-group">';
        $form.= '<label for="secretkey">' . __('Password', 'wp-squirrelmail') . '</label>';
        $form.= '<input type="password" id="password" class="form-control"  name="secretkey"'
                . 'required="required" placeholder="' . __('Password', 'wp-squirrelmail') . '" '
                . 'value="' . $this->getPassword() . '">' . "\n";
        $form.= '</div>';
        
        $form.= '<button type="submit" class="btn btn-default" id="wpsquirrelmail-submit">' . __('Login', 'wp-squirrelmail') . '</button>' . "\n";
        $form.= '</form>';
        $form.= '</div>';
        $form.= '</div>';
        
        return $form;
    }
}
