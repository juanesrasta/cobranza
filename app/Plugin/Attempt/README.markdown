Attempt Plugin
==============

A CakePHP 2.0 or 2.1 plugin that helps to protect sensitive actions from brute force attacks.

API
---

### count($action)
Returns the number of failed attempts for a certain action.

### limit($action, $limit = 5)
Returns false if the number of failed attempts is bigger than the passed limit.

### fail($action, $duration = '+10 minutes')
Creates a failed attempt that counts towards the limit for the passed duration

### reset($action)
Deletes all failed attempts for a certain action

### cleanup()
Deletes all expired failed attempts from the database. This should be run via CakeShell (ideally as a CRON job) every now and then. 


Schema
------

Run `Console/cake schema create --plugin Attempt` or manually make a table:

    CREATE TABLE `attempts` (
      `id` char(36) NOT NULL DEFAULT '',
      `ip` varchar(64) DEFAULT NULL,
      `action` varchar(32) DEFAULT NULL,
      `created` datetime DEFAULT NULL,
      `expires` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `ip` (`ip`,`action`),
      KEY `expires` (`expires`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8


Example Implementation
----------------------
    
    <?php
    class ExampleController extends AppController {
        
        public $components = array(
            'Attempt.Attempt'
        );
        
        public $loginAttemptLimit = 10;
        public $loginAttemptDuration = '+1 hour';
        
        public function login() {
            // Form submitted?
            if ( $formSubmitted = true ) {
                // All required fields entered?
                if ( $validFormData = true ) {
                    // Limit to 10 failed attempts
                    if ( $this->Attempt->limit('login', $this->loginAttemptLimit) ) {
                        // Validate user credentials
                        if ( $validCredentials = true ) {
                            // Log user in
                        } else {
                            // Invalid credentials, count as failed attempt for an hour
                            $this->Attempt->fail('login', $this->loginAttemptDuration);
                            $this->Session->setFlash('Unknown user or wrong password');
                        }
                    } else {
                        // User exceeded attempt limit
                        // Ideally show a CAPTCHA (ensuring this is not a robot 
                        // without blocking out and frustrating users),
                        // otherwise show error message
                        $this->Session->setFlash('Too many failed attempts!');
                    }
                } else {
                    // Invalid form data but keep it ambiguous
                    $this->Session->setFlash('Unknown user or wrong password');
                }
            }
        }
    }


Alternate Implementation (simple admin login)
---------------------------------------------
    
    <?php
    class UsersController extends AppController {
        
        public $components = array(
            'Security',
            'Attempt.Attempt'
        );
        
        public $loginAttemptLimit = 10;
        public $loginAttemptDuration = '+1 hour';

        public function admin_login() {
            if (empty($this->data)) {
                return;
            }
            // check for repeated login attempts
            if ($this->Attempt->limit('admin_login', $this->loginAttemptLimit)) {
                if ($this->request->is('post')) {
                    if ($this->Auth->login()) {
                        // login was successful, redirect to admin menu
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'index',
                            'admin' => true
                        ));
                    } else {
                        // increment the attempt counter
                        $this->Attempt->fail('admin_login', $this->loginAttemptDuration);
                        $this->Session->setFlash('Unknown user or wrong password.');
                        return;
                    }
                }
            } else {
                // $loginAttemptLimit reached
                $this->Session->setFlash('Login limit exceeded, please try again later.');
            }
        }
    }
