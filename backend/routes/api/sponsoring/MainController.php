<?php
namespace Routes\Api\Sponsoring;

use Base\BaseControllerRoute;

class MainContoller extends BaseControllerRoute
{
    public function Init()
    {
        $this->response = $this->response->withJson([
            "monero" => "47PuHf2VrHXefscQFBvMD6VqZYZrtSErfKTVE4woyTdpTTFsP4jn8S9DqK5faj6ctgPqRBkWLuqLBhtADqQFiDrjUEiUiMz"
        ]);
    }
}