<?php
class FirstCest
{
    /**
     * Test start tournament
     *
     * @param \AcceptanceTester $I
     */
    public function tournamentStart(AcceptanceTester $I)
    {
        $I->wantTo('Check that Main page and Tournament are work');
        $I->amOnPage('/');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see('Let\'s, start!');
        $I->click(['class'=>'btn-primary']);
        $tour = $I->seeCurrentUrlMatches('~^/tournament/(\d+)~');
        $I->see("Okay!");
    }
}


