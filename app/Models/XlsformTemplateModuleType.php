<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class XlsformTemplateModuleType extends Model
{
    protected $table = 'xlsform_template_module_types';

    public function xlsformTemplateModules(): HasMany
    {
        return $this->hasMany(XlsformTemplateModule::class);
    }


}
