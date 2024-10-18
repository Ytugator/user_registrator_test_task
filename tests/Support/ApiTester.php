<?php

declare(strict_types=1);

namespace Tests\Support;
use Faker;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;


    public string $userRegUrl = 'user/create/';
    /**
     * Define custom actions here
     */
    public function makeUserData($username = null, $email = null, $password = null)
    {
        $faker = Faker\Factory::create();

        return [
            'username' => $username ?? $faker->userName(),
            'email'    => $email ?? $faker->email(),
            'password' => $password ?? $faker->password(),
        ];
    }
    public function responseIsSuccess(): void
    {
        $I = $this;
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":true');
    }

    public function makeUserRegistration($inputData = null)
    {
        $I = $this;
        $userData = ['inputData' => $inputData ?? $I -> makeUserData()];
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost($I->userRegUrl, $userData['inputData']);
        try {
            $respData = $I->grabDataFromResponseByJsonPath('$.details')[0];
            $userData += ['respData' => $respData];
        } catch (\Exception $e) {}
        return $userData;
    }
}
