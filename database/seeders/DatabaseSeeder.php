<?php

namespace Database\Seeders;

use App\Models\BlogComment;
use App\Models\BlogCommentReply;
use App\Models\MarketOffer;
use App\Models\OfferPackage;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(LawyerSeeder::class);
        // $this->call(LawyerAboutSeeder::class);
        $this->call(SpecialitySeeder::class);
        // $this->call(LawyerSpecialitySeeder::class);
        // $this->call(CommunityCategorySeeder::class);
        // $this->call(CommunitySubCategorySeeder::class);
        // $this->call(CommunityServiceSeeder::class);
        // $this->call(MarketProductCategorySeeder::class);
        // $this->call(MarketProductSeeder::class);
        // $this->call(MarketOfferSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(OfferPackageSeeder::class);
        $this->call(AdSeeder::class);
        // $this->call(CourtCaseSeeder::class);
        // $this->call(CourtCaseEventSeeder::class);
        // $this->call(CourtCaseUpdateSeeder::class);
        $this->call(CourtCaseLevelSeeder::class);
        // $this->call(CourtCaseDueSeeder::class);
        // $this->call(CourtCaseCancellationSeeder::class);
        $this->call(LawyerPackageSeeder::class);
        // $this->call(WalletTransactionSeeder::class);
        //  $this->call(LawyerTimeSeeder::class);
        // $this->call(NotificationSeeder::class);
        // $this->call(OrderSeeder::class);
        // $this->call(BlogSeeder::class);
        // $this->call(BlogCommentSeeder::class);
        // $this->call(BlogCommentReplySeeder::class);
        // $this->call(BlogReactionSeeder::class);
        $this->call(SettingSeeder::class);
        // $this->call(RateSeeder::class);
        // $this->call(SosRequestSeeder::class);
        // $this->call(RefuseReasonSeeder::class);
        // $this->call(ClientPointSeeder::class);
        // $this->call(ContractCategorySeeder::class);
        // $this->call(ContractFileSeeder::class);

    }
}
