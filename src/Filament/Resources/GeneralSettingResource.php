<?php

namespace Josefo727\FilamentGeneralSettings\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Josefo727\FilamentGeneralSettings\Filament\Resources\GeneralSettingResource\Pages;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;

class GeneralSettingResource extends Resource
{
    protected static ?string $model = GeneralSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function getNavigationLabel(): string
    {
        return __('filament-general-settings::general.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return config('filament-general-settings.navigation.group', __('filament-general-settings::general.navigation_group'));
    }

    public static function getNavigationSort(): int
    {
        return config('filament-general-settings.navigation.sort', 1);
    }

    public static function getModelLabel(): string
    {
        return __('filament-general-settings::general.title');
    }

    public static function form(Form $form): Form
    {
        $dataTypeService = new DataTypeService();
        $types = $dataTypeService->getTypesForSelect();

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-general-settings::general.fields.name'))
                            ->placeholder(__('filament-general-settings::general.placeholders.name'))
                            ->required()
                            ->unique(GeneralSetting::getTableName(), 'name', true)
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label(__('filament-general-settings::general.fields.type'))
                            ->placeholder(__('filament-general-settings::general.placeholders.type'))
                            ->options($types)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('value', '')),

                        Forms\Components\TextInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'string')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->numeric()
                            ->integer()
                            ->visible(fn (callable $get) => $get('type') === 'integer'),

                        Forms\Components\TextInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->numeric()
                            ->visible(fn (callable $get) => $get('type') === 'float'),

                        Forms\Components\Toggle::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->visible(fn (callable $get) => $get('type') === 'boolean')
                            ->onColor('success')
                            ->offColor('danger')
                            ->required(),

                        Forms\Components\TagsInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'array')
                            ->separator(',')
                            ->rules(['array', 'min:1'])
                            ->validationMessages([
                                'min' => __('filament-general-settings::validation.array_min'),
                            ]),

                        Forms\Components\Textarea::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'json')
                            ->rows(5)
                            ->rule('json', fn () => __('filament-general-settings::validation.json')),

                        Forms\Components\DatePicker::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'date'),

                        Forms\Components\TimePicker::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'time')
                            ->seconds()
                            ->format('H:i:s'),

                        Forms\Components\DateTimePicker::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'datetime')
                            ->seconds()
                            ->format('Y-m-d H:i:s'),

                        Forms\Components\TextInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->url()
                            ->visible(fn (callable $get) => $get('type') === 'url')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->email()
                            ->visible(fn (callable $get) => $get('type') === 'email')
                            ->maxLength(255),

                        Forms\Components\TagsInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->visible(fn (callable $get) => $get('type') === 'emails')
                            ->separator(',')
                            ->rules(['array', function ($attribute, $value, $fail) {
                                foreach ($value as $email) {
                                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        $fail(__('filament-general-settings::validation.email', ['email' => $email]));
                                        break;
                                    }
                                }
                            }]),

                        Forms\Components\TextInput::make('value')
                            ->label(__('filament-general-settings::general.fields.value'))
                            ->placeholder(__('filament-general-settings::general.placeholders.value'))
                            ->required()
                            ->password()
                            ->visible(fn (callable $get) => $get('type') === 'password')
                            ->maxLength(255)
                            ->minLength(4)
                            ->validationMessages([
                                'min' => __('filament-general-settings::validation.password_min', ['min' => 4]),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-general-settings::general.fields.description'))
                            ->placeholder(__('filament-general-settings::general.placeholders.description'))
                            ->rows(3)
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-general-settings::general.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valueForDisplay')
                    ->label(__('filament-general-settings::general.fields.value'))
                    ->searchable(function (Builder $query, string $search): Builder {
                        return $query->where('value', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-general-settings::general.fields.type'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament-general-settings::general.fields.description'))
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-general-settings::general.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneralSettings::route('/'),
            'create' => Pages\CreateGeneralSetting::route('/create'),
            'edit' => Pages\EditGeneralSetting::route('/{record}/edit'),
        ];
    }
}
