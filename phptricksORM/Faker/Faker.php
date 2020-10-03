<?php

/**
 * Soon....
 * create fake data, and insert into database
 * Imagine to how to use :
 */

/**
 class UsersFaker extends Faker
{
    public function run(UserModel::class)
    {
        return [
            'username' => $this->faker->string(Faker::NAME),
            // 'email'    => $this->faker->string(Faker::EMAIL, **unique** true),
            'email'    => $this->faker->unique()->string(Faker::EMAIL),
            'joined' => $this->faker->dateTime(),
            'password' => $this->faker->password(),
            'remember_token' => $this->faker->string(Faker::TOKEN)

        ];
    }
}
**/

// command
/**
 * php phptricks faker:make ModelName
 * ----------------------------------
 * to run faker and insert into database
 * php phptricks faker:run
 */

//namespace PHPtricks\Orm\Faker;

/**
 * Class Faker
 * insert fake data from database tables
 * @package PHPtricks\Orm\Faker
 */
/*class Faker
{

}*/