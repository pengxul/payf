<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\User;

use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/02fkaq?ref=api
 */
class AgreementExecutionPlanModifyPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.user.agreement.executionplan.modify';
    }
}
