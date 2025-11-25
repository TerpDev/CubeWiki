<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Filament\Resources\Applications\ApplicationsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApplications extends EditRecord
{
    protected static string $resource = ApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
