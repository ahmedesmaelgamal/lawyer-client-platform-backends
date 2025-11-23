<?php

namespace App\Enums;

enum ReasonEnum: string
{
    case COMPLETED_AND_CLOSED = 'completed_and_closed';
    case NON_COMPLIANCE_WITH_DEADLINES = 'non_compliance_with_deadlines';
    case LACK_OF_SUFFICIENT_FOLLOW_UP = 'lack_of_sufficient_follow_up';
    case INSUFFICIENT_REQUIRED_INFORMATION = 'insufficient_required_information';
    case WITHDRAWAL_OF_A_PARTY = 'withdrawal_of_a_party';
    case INSUFFICIENT_BUDGET = 'insufficient_budget';
    case DISAGREEMENT_BETWEEN_PARTIES = 'disagreement_between_parties';
    case NO_NEED_FOR_CONTINUATION = 'no_need_for_continuation';
    case COMPLEXITY_OF_PROCEDURES = 'complexity_of_procedures';
    case PERSONAL_OR_HEALTH_REASONS = 'personal_or_health_reasons';
    case CHANGE_IN_PRIORITIES = 'change_in_priorities';
    case REJECTION_BY_THE_CONCERNED_AUTHORITY = 'rejection_by_the_concerned_authority';
    case LACK_OF_SUPPORT_OR_RESOURCES = 'lack_of_support_or_resources';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang()
    {
        return match ($this) {
            self::COMPLETED_AND_CLOSED => trns('completed_and_closed'),
            self::NON_COMPLIANCE_WITH_DEADLINES => trns('non_compliance_with_deadlines'),
            self::LACK_OF_SUFFICIENT_FOLLOW_UP => trns('lack_of_sufficient_follow_up'),
            self::INSUFFICIENT_REQUIRED_INFORMATION => trns('insufficient_required_information'),
            self::WITHDRAWAL_OF_A_PARTY => trns('withdrawal_of_a_party'),
            self::INSUFFICIENT_BUDGET => trns('insufficient_budget'),
            self::DISAGREEMENT_BETWEEN_PARTIES => trns('disagreement_between_parties'),
            self::NO_NEED_FOR_CONTINUATION => trns('no_need_for_continuation'),
            self::COMPLEXITY_OF_PROCEDURES => trns('complexity_of_procedures'),
            self::PERSONAL_OR_HEALTH_REASONS => trns('personal_or_health_reasons'),
            self::CHANGE_IN_PRIORITIES => trns('change_in_priorities'),
            self::REJECTION_BY_THE_CONCERNED_AUTHORITY => trns('rejection_by_the_concerned_authority'),
            self::LACK_OF_SUPPORT_OR_RESOURCES => trns('lack_of_support_or_resources'),
            self::OTHER => trns('other'),
        };
    }
}
