<?php
namespace Api\Handlers;

use Base\BaseHandlerRoute;

class SponsoringHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->response = $this->response->withStatus(200)->withJson([
            "monero" => "47PuHf2VrHXefscQFBvMD6VqZYZrtSErfKTVE4woyTdpTTFsP4jn8S9DqK5faj6ctgPqRBkWLuqLBhtADqQFiDrjUEiUiMz"
        ]);
    }
}