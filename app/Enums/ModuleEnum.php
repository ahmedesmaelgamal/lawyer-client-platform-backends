<?php

namespace App\Enums;

enum ModuleEnum: string
{
    //----------------------
    // System Admin Modules
    //----------------------


    case ADMIN_MANAGEMENT = 'admin_management';
    case SETTINGS_MANAGEMENT = 'settings_management';
    case ROLES_MANAGEMENT = 'roles_management';
    case CONTRACT_FILES_MANAGEMENT = 'contract_files_management';
    case CONTRACT_CATEGORYS_MANAGEMENT = 'contract_categorys_management';
    case BLOGS_MANAGEMENT = 'blogs_management';
    case BLOG_ACTIONS_MANAGEMENT = 'blog_actions_management';
    case BLOG_FILES_MANAGEMENT = 'blog_files_management';
    case ACTIVITY_LOGS_MANAGEMENT = 'activity_logs_management';
    case BLOG_COMMENTS_MANAGEMENT = 'blog_comments_management';
    case BLOG_COMMENT_REPLY_MANAGEMENT = 'blog_comment_reply_management';
    case COURT_CASE_DUES_MANAGEMENT = 'court_case_dues_management';
    case CLIENT_MANAGEMENT = 'client_management';
    case LAWYER_MANAGEMENT = 'lawyer_management';
    case LAWYER_TIMES_MANAGEMENT = 'lawyer_times_management';
    case RATES_MANAGEMENT = 'rates_management';
    case AD_MANAGEMENT = 'ad_management';
    case LEVEL_MANAGEMENT = 'level_management';
    case SPECIALITY_MANAGEMENT = 'speciality_management';
    case ORDERS_MANAGEMENT = 'orders_management';
    case PRODUCT_DISCOUNTS_MANAGEMENT = 'product_discounts_management';
    case OFFER_PACKAGE_MANAGEMENT = 'offer_package_management';
    case LAWYER_PACKAGE_MANAGEMENT = 'lawyer_package_management';
    case COUNTRY_MANAGEMENT = 'country_management';
    case CITY_MANAGEMENT = 'city_management';
    case COMMUNITY_MANAGEMENT = 'community_management';
    case MARKET_MANAGEMENT = 'market_management';
    case MARKET_OFFERS_MANAGEMENT = 'market_offers_management';
    case MARKET_PRODUCT_CATEGORIES_MANAGEMENT = 'market_product_categories_management';
    case COURT_CASE_MANAGEMENT = 'court_case_management';
    case COURT_CASE_file_MANAGEMENT = 'court_case_file_management';
    case CASE_SPECIALIZATION_MANAGEMENT = 'case_specialization_management';
    case SETTING_MANAGEMENT = 'setting_management';
    case WALLET_MANAGEMENT = 'wallet_management';
    case WALLET_TRANSACTION_MANAGEMENT = 'wallet_transaction_management';
    case LAWYER_ABOUT_MANAGEMENT = 'lawyer_about_management';
    case CHAT_MANAGEMENT = 'chat_management';
    case CHAT_ROOM_MANAGEMENT = 'chat_room_management';
    case NOTIFICATION_MANAGEMENT = 'notification_management';
    case NOTIFICATION_USER_MANAGEMENT = 'notification_user_management';
    case USER_POINT_MANAGEMENT = 'user_point_management';
    case    DEVICE_TOKEN_MANAGEMENT = 'device_token_management';
    case    FREE_CONSULTATION_REPLY_MANAGEMENT = 'free_consultation_reply_management';
    case    FREE_CONSULTATION_REQUEST_MANAGEMENT = 'free_consultation_request_management';
    case    COMMUNITY_SERVICE_MANAGEMENT = 'community_service_management';
    case    COMMUNITY_SUB_CATEGORIE_MANAGEMENT = 'community_sub_categorie_management';
    case    COMMUNITY_CATEGORIE_MANAGEMENT = 'community_categorie_management';
    case    DOCUMENT_MANAGEMENT = 'document_management';
    case    DOCUMENT_CATEGORIE_MANAGEMENT = 'document_categorie_management';
    case     SOS_REQUEST_MANAGEMENT = 's_o_s_request_management';
    case     CLIENT_POINT_MANAGEMENT = 'client_point_management';
    case     POINT_TRANSACTION_MANAGEMENT = 'point_transaction_management';
    case       WITHDRAW_REQUEST_MANAGEMENT = 'withdraw_request_management';
    case       PAYMENT_LOG_MANAGEMENT = 'payment_log_management';
    case       PAYMOB_MANAGEMENT = 'paymob_management';
    case        OFFICE_REQUEST_MANAGEMENT = 'office_request_management';
    case        COURT_CASE_HISTORY_MANAGEMENT = 'court_case_history_management';
    case        BLOG_REACTION_MANAGEMENT = 'blog_reaction_management';
    case        EMAIL_OTP_MANAGEMENT = 'email_otp_management';
    case        REFUSE_REASON_MANAGEMENT = 'refuse_reason_management';
    case        PERMISSION_MANAGEMENT = 'permission_management';
    case        LAWYER_REPORT_MANAGEMENT = 'lawyer_report_management';

    //    case SOS_REQUEST_MANAGEMENT= 'sos_request_management';
    public function lang(): string
    {
        return trns($this->value);
    }

    public function permissions(): array
    {
        return [
            'create_' . $this->value,
            'read_' . $this->value,
            'update_' . $this->value,
            'delete_' . $this->value
        ];
    }
}
