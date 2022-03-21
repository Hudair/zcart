<?php

use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('BannerGroupsSeeder');
        $this->call('TimezonesSeeder');
        $this->call('CurrenciesSeeder');
        $this->call('CountriesSeeder');
        $this->call('StatesSeeder');
        $this->call('RolesSeeder');
        $this->call('SystemsSeeder');
        $this->call('UsersSeeder');
        $this->call('ModulesSeeder');
        $this->call('PermissionSeeder');
        $this->call('AttributeSeeder');
        $this->call('GtinSeeder');
        $this->call('PaymentMethodsSeeder');
        $this->call('AddressTypesSeeder');
        $this->call('TicketCategoriesSeeder');
        $this->call('DisputeTypesSeeder');
        $this->call('TaxesSeeder');
        $this->call('PackagingsSeeder');
        $this->call('SubscriptionPlansSeeder');
        $this->call('PagesSeeder');
        $this->call('FaqsSeeder');
        $this->call('LanguagesSeeder');
        $this->call('CancellationReasonSeeder');
        // $this->call('OptionTableSeeder');
        // $this->call('demoSeeder');
        $this->command->info('Seeding complete!');

        Model::reguard();
    }
}
