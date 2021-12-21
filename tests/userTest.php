<?php
require_once("./src/User.php");
use PHPUnit\Framework\TestCase;


final Class userTest extends TestCase{

    public function testLogin(): void
    {
        $userClass = new User();
        
        $trueResponse = $userClass->login("user", "customer");
        $falseResponse =$userClass->login("user", "falsetest");

        $trueAdmin =$userClass->isAdmin("admin");
        $falseAdmin =$userClass->isAdmin("falsetest");

        $this->assertSame(true, $trueResponse);
        $this->assertSame(false, $falseResponse);

        $this->assertSame(true, $trueAdmin);
        $this->assertSame(false, $falseAdmin);
    }
}

?>