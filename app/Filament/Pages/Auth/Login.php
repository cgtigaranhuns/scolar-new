<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function getHeading(): string|\Illuminate\Support\HtmlString
    {
        return 'SCOLAR';
    }

    public function getSubheading(): string|\Illuminate\Support\HtmlString
    {
        return 'Sistema de Conselhos de Classe e Acompanhamento de Estudantes';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('Matrícula')
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $connection = strlen($state) < 11 ? 'adm' : 'labs';
                        $set('ldap_connection', $connection);
                    })
                    ->validationMessages([
                        'required' => 'O campo matrícula é obrigatório',
                    ]),

                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable()
                    ->required()
                    ->validationMessages([
                        'required' => 'O campo senha é obrigatório',
                    ]),

                Hidden::make('ldap_connection')
                    ->default('adm'),

                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username'   => $data['username'],
            'password'   => $data['password'],
            'connection' => $data['ldap_connection'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            'data.password' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}