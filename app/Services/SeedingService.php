<?php

namespace App\Services;

use App\Models\Customer;
use Faker\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class SeedingService
 *
 * @package App\Services
 */
class SeedingService
{
    /**
     * This function is simulating a db recordset of validated customer data
     *
     * @param int $numberOfRecords
     * @return Collection
     */
    public function seedDBData(int $numberOfRecords = 5): Collection
    {
        $fakerFactory = new Factory();
        $faker = $fakerFactory::create();

        for ($i = 0; $i < $numberOfRecords; $i++) {
            $dummyData[] = new Customer([
                'name' => $faker->firstName,
                'tel'  => $faker->phoneNumber,
                'mail' => $faker->email
            ]);
        }

        return collect($dummyData ?? []);
    }

    /**
     * This function reads a file from storage and makes 2 validation rounds.
     * 1. Validated for basic structure (includes : and ,)
     * 2. Validated for basic format rules
     * The result is returns in an array of valid customers, invalid lines and invalid customers
     *
     * @return array
     */
    public function seedFileData(): array
    {
        $file = Storage::disk('public')
            ->get('customers.txt');
        if ($file) {
            $lines = preg_split("/\r?\n|\r/", $file);
            foreach ($lines as $line) {
                if ($customer = $this->validateLine($line)) {
                    $fileCustomers[] = $customer;
                } else {
                    $invalidLines[] = $line; // we keep the invalid lines in case we want to handle them
                }
            }
        }

        // Validate the data
        foreach ($fileCustomers ?? [] as $fileCustomer) {
            $validator = Validator::make($fileCustomer->toArray(), [
                'name' => 'required|string|min:2|alpha',
                'mail' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'tel'  => 'required|regex:/^([+\-\( 0-9]+)?([0-9\+\-\( ]{10,13})$/'
            ]);
            if (!$validator->fails()) {
                $validFileCustomers[] = $fileCustomer;
            } else {
                $invalidFileCustomers[] = ['customer' => $fileCustomer, 'errors' => $validator->errors()];
            }
        }

        return [collect($validFileCustomers ?? []), $invalidLines ?? [], $invalidFileCustomers ?? []];
    }

    /**
     * @param string $line
     * @return Customer|false
     */
    private function validateLine(string $line): Customer|bool
    {
        if ($this->hasOneColonAndOneComma($line)) {
            // has all elements
            $nameArray = explode(':', $line);
            $telAndEmailArray = explode(',', $nameArray[1]);

            return new Customer([
                'name' => $nameArray[0],
                'tel'  => $telAndEmailArray[0],
                'mail' => $telAndEmailArray[1]
            ]);
        } else {
            // is missing some basic elements
            return false;
        }
    }

    /**
     * This function does a basic check if the line string contains one : and one ,
     *
     * @param string $string
     * @return bool
     */
    private function hasOneColonAndOneComma(string $string): bool
    {
        return substr_count($string, ':') === 1 && substr_count($string, ',') === 1;
    }
}
