<?php

    $this->dispatcher->connect('application.throw_exception', array('rxErrbitLogger', 'logThrownException'));
