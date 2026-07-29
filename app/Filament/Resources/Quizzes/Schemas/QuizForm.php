<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('quizzes')
                    ->imageEditor()
                    ->columnSpanFull(),

                Textarea::make('instruction')
                    ->label('Instructions')
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('difficulty_id')
                    ->label('Difficulty')
                    ->relationship('difficulty', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                CheckboxList::make('categories')
                    ->relationship('categories', 'name')
                    ->required()
                    ->columns(2)
                    ->columnSpanFull(),

                TextInput::make('time')
                    ->label('Time (minutes)')
                    ->numeric()
                    ->required(),

                Repeater::make('questions')
                    ->relationship()
                    ->schema([
                        TextInput::make('text')
                            ->label('Question')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('points')
                            ->label('Points')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpanFull(),

                        Repeater::make('answers')
                            ->relationship()
                            ->schema([
                                TextInput::make('text')
                                    ->label('Answer')
                                    ->required(),

                                Checkbox::make('is_correct')
                                    ->label('Correct'),
                            ])
                            ->columns(2)
                            ->defaultItems(2)
                            ->minItems(2)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->defaultItems(1)
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
