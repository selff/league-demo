<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Repositories\MembersInterface;

class MemberController extends Controller
{
    private $members;

    public function __construct(MembersInterface $members)
    {
        $this->members = $members;
    }

}
