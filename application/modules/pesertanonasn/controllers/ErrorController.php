<?php 

    class Pesertanonasn_ErrorController extends Zend_Controller_Action
    {
        public function errorAction()
        {
            // Get the error object from the request
            $errors = $this->_getParam('error_handler');
            
            // Clear the previous content and set a new response
            $this->getResponse()->clearBody();

            // Set a generic error message
            $errorMessage = 'An error occurred. Please try again later.';
            
            // Check if the error is an exception
            if ($errors && $errors->exception instanceof Exception) {
                // Log or handle the exception as needed
                
                // Customize the error message for specific exceptions
                if ($errors->exception instanceof Zend_Db_Exception) {
                    $errorMessage = 'A database error occurred.';
                } elseif ($errors->exception instanceof Zend_Controller_Router_Exception) {
                    $errorMessage = 'Invalid URL or routing error.';
                }
                
                // Additional customizations for specific exceptions can be added here
            }
            
            // Set the view variables for the error message
            $this->view->message = $errorMessage;

            // Render the error view
            $this->_redirect('/peserta/error');
        }
    }

?>