<?php

/**
 * authModel
 * Authentication plugin interface to standardize plugins behaviour and I/O
 * streams.
 *
 * @package Auth
 * @version 0.1.2
 * @copyright Copyright (c) 2010 Serenity Media
 * @author  Victor Nițu <victor@serenitymedia.ro>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt AGPLv3
 */
interface authModel {

    public function __construct($user, $token);
    public function connectService();
    public function checkAuth();

}
