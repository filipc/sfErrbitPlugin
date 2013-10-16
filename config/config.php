<?php

    $this->dispatcher->connect('application.throw_exception', array('sfErrbitLogger', 'logThrownException'));
