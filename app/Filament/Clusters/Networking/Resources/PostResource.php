<?php

namespace App\Filament\Clusters\Networking\Resources;

use Closure;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Clusters\Networking;
use App\Filament\Clusters\Networking\Resources\PostResource\Pages;
use App\Filament\Clusters\Networking\Resources\PostResource\RelationManagers;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use Illuminate\Support\Collection;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Events';

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    protected static ?string $cluster = Networking::class;

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])->schema([
                    // Main content area (spans 2 columns)
                    Forms\Components\Group::make()->schema([
                        Forms\Components\Section::make('Event Information')
                            ->description('Basic event details and SEO optimization')
                            ->icon('heroicon-o-information-circle')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Placeholder::make('info')
                                    ->label('')
                                    ->content('Fill in the event details below. Make sure to optimize for SEO to improve visibility.')
                                    ->columnSpanFull(),
                                    
                                Forms\Components\Section::make('SEO Title & Description')
                                    ->description('These elements appear in search results')
                                    ->schema([
                                TextInput::make('name')
                                    ->label('SEO Title')
                                    ->helperText(str('✨ Title Optimization Tips:
                                    - Start with main keyword (most important)
                                    - Keep between 50-60 characters
                                    - Include your brand name if space allows
                                    - Use power words (e.g., Ultimate, Guide, Best)
                                    - Match search intent')->inlineMarkdown()->toHtmlString())
                                    ->translatable(true, null, [
                                        'en' => ['string', 'max:60', 'min:10'],
                                        'es' => ['string', 'max:60', 'min:10'],
                                        'fr' => ['string', 'max:60', 'min:10'],
                                    ])
                                    ->columnSpanFull(),
    
                                TextInput::make('meta_description')
                                    ->label('Meta Description')
                                    ->helperText(str('✨ Description Best Practices:
                                    - Include primary & secondary keywords
                                    - Keep between 150-160 characters
                                    - Add a clear call-to-action
                                    - Match title intent
                                    - Make it compelling & clickable')->inlineMarkdown()->toHtmlString())
                                    ->translatable(true)
                                    ->columnSpanFull(),
    
                                TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->hiddenOn(['create'])
                                    ->helperText(str('✨ URL Optimization Tips:
                                    - Include main keyword
                                    - Keep it short (3-5 words)
                                    - Use hyphens between words
                                    - Avoid numbers unless necessary
                                    - Avoid updating once published bcs can break links
                                    - Make it readable')->inlineMarkdown()->toHtmlString())
                                    ->translatable(
                                        true,
                                        null,
                                        [
                                            'en' => ['string', 'max:255', 'min:10', 'regex:/^[a-zA-Z0-9-_]+$/'],
                                            'es' => ['string', 'max:255', 'min:10', 'regex:/^[a-zA-Z0-9-_]+$/'],
                                            'fr' => ['string', 'max:255', 'min:10', 'regex:/^[a-zA-Z0-9-_]+$/'],
                                        ]
                                    )
                                    ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Forms\Components\Section::make('Event Content')
                            ->description('Write engaging content for your event')
                            ->icon('heroicon-o-document-text')
                            ->collapsible()
                            ->schema([
                                Translate::make()
                                    ->locales(['en', 'es', 'fr'])
                                    ->schema([
                                        Forms\Components\MarkdownEditor::make('content')
                                            ->label('Article Content')
                                            ->helperText(str('✨ Content SEO Guidelines:
                                            - Write 1500+ words for comprehensive coverage
                                            - Use H2s & H3s with keywords
                                            - Include primary keyword in first 100 words
                                            - Add 2-3 internal links
                                            - Add 2-3 authoritative external links
                                            - Use bullet points & short paragraphs
                                            - Include relevant images with alt text
                                            - Optimize for featured snippets
                                            - Answer common user questions
                                            - Use transition words for readability')->inlineMarkdown()->toHtmlString())
                                            ->disableToolbarButtons(['table'])
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull()
                                    ->suffixLocaleLabel(),
                            ]),
                    ])->columnSpan(2),

                    // Sidebar (spans 1 column)
                    Forms\Components\Group::make()->schema([
                        Forms\Components\Section::make('SEO Metadata')
                            ->description('Optimize your content for search engines')
                            ->icon('heroicon-o-magnifying-glass')
                            ->collapsible()
                            ->schema([
                                TagsInput::make('keywords')
                                    ->label('Focus Keywords')
                                    ->helperText(str('✨ Keyword best practices:
                                - Include 1 primary keyword
                                - Add 2-3 related keywords
                                - Use specific long-tail keywords
                                - Match search intent')->inlineMarkdown()->toHtmlString())
                                    ->splitKeys(['Tab', ', '])
                                    ->separator(',')
                                    ->translatable(true),

                                Forms\Components\TextInput::make('time_to_read')
                                    ->label('Reading Time (minutes)')
                                    ->helperText('Helps users decide whether to read now or save for later')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(60)
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Featured Image')
                            ->description('Visual content improves engagement')
                            ->icon('heroicon-o-photo')
                            ->collapsible()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('thumbnail')
                                    ->label('Cover Image')
                                    ->helperText(str('✨ Image optimization tips:
                                - Use 1200×800px size
                                - Compress without quality loss
                                - Use relevant images')->inlineMarkdown()->toHtmlString())
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('3:2')
                                    ->required()
                                    ->collection('thumbnail'),
                            ]),

                        Forms\Components\Section::make('Content Organization')
                            ->description('Categorize and organize your event')
                            ->icon('heroicon-o-folder')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->helperText('Group similar content for better SEO')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                    // author_id
                                Forms\Components\Select::make('author_id')
                                    ->relationship('author', 'name')
                                    ->helperText('Select the author of this post')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
    
                                SpatieTagsInput::make('tags')
                                    ->label('Topic Tags')
                                    ->helperText(str('✨ Topic Organization:
                                    - Use relevant industry terms
                                    - Include keyword variations
                                    - Add related subtopics
                                    - Stay consistent with tags')->inlineMarkdown()->toHtmlString())
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Publishing Settings')
                            ->description('Control when and how your event is published')
                            ->icon('heroicon-o-globe-alt')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published')
                                    ->helperText('Make visible to search engines')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Content')
                                    ->helperText('Prioritize in sitemaps'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->helperText('Fresh content ranks better')
                                    ->required(),
                            ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            // Thumbnail & Basic Info
            SpatieMediaLibraryImageColumn::make('thumbnail')
                ->label('Cover')
                ->collection('thumbnail')
                ->circular()
                ->defaultImageUrl(url('/images/placeholder.png'))
                ->size(50)
                ->extraAttributes(['class' => 'rounded-lg']),
                
            Tables\Columns\TextColumn::make('name')
                ->label('Event Title')
                ->searchable()
                ->sortable()
                ->limit(50)
                ->weight('bold')
                ->color('primary')
                ->formatStateUsing(function ($state) {
                    if (is_array($state)) {
                        // Get first available translation or fallback to ID
                        return $state['en'] ?? $state['fr'] ?? $state['es'] ?? 'Untitled';
                    }
                    return $state ?? 'Untitled';
                })
                ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();
                    if (is_array($state)) {
                        $state = $state['en'] ?? $state['fr'] ?? $state['es'] ?? 'Untitled';
                    }
                    return strlen($state) > 50 ? $state : null;
                }),

            // Author & Category
            Tables\Columns\TextColumn::make('author.name')
                ->label('Author')
                ->searchable()
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('info')
                ->toggleable(),

            // SEO & Analytics
            Tables\Columns\TextColumn::make('keywords')
                ->label('Keywords')
                ->searchable()
                ->toggleable()
                ->limit(30),

            Tables\Columns\TextColumn::make('time_to_read')
                ->label('Read Time')
                ->suffix(' min')
                ->sortable()
                ->toggleable()
                ->badge()
                ->color('gray'),

            Tables\Columns\TextColumn::make('views_count')
                ->label('Views')
                ->sortable()
                ->toggleable()
                ->badge()
                ->color('success')
                ->formatStateUsing(fn ($state) => number_format($state)),

            // Status Indicators
            Tables\Columns\IconColumn::make('is_published')
                ->label('Status')
                ->boolean()
                ->sortable()
                ->toggleable()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger')
                ->size('lg'),

            Tables\Columns\IconColumn::make('is_featured')
                ->label('Featured')
                ->boolean()
                ->sortable()
                ->toggleable()
                ->trueIcon('heroicon-o-star')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('warning')
                ->size('lg'),

            // Dates
            Tables\Columns\TextColumn::make('published_at')
                ->label('Published')
                ->dateTime('M j, Y')
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Last Updated')
                ->dateTime('M j, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->defaultSort('created_at', 'desc')
        ->filters([
            Tables\Filters\TrashedFilter::make(),
            Tables\Filters\SelectFilter::make('category')
                ->relationship('category', 'name')
                ->multiple()
                ->preload(),
            Tables\Filters\SelectFilter::make('author')
                ->relationship('author', 'name')
                ->multiple()
                ->preload(),
            Tables\Filters\TernaryFilter::make('is_published')
                ->label('Published Status')
                ->placeholder('All Posts')
                ->trueLabel('Published Posts')
                ->falseLabel('Draft Posts'),
            Tables\Filters\TernaryFilter::make('is_featured')
                ->label('Featured Status'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()
                ->color('info')
                ->icon('heroicon-o-eye')
                ->url(fn (Post $record): string => static::getUrl('view', ['record' => $record->getKey()])),
            Tables\Actions\EditAction::make()
                ->color('warning')
                ->icon('heroicon-o-pencil')
                ->url(fn (Post $record): string => static::getUrl('edit', ['record' => $record->getKey()])),
            Tables\Actions\DeleteAction::make()
                ->color('danger')
                ->icon('heroicon-o-trash'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\BulkAction::make('togglePublish')
                    ->label('Toggle Publication')
                    ->icon('heroicon-o-globe-alt')
                    ->action(function (Collection $records): void {
                        foreach ($records as $record) {
                            $record->update(['is_published' => !$record->is_published]);
                        }
                    }),
            ]),
        ])
        ->emptyStateHeading('No events yet')
        ->emptyStateDescription('Start creating your first event post to engage with your audience.')
        ->emptyStateIcon('heroicon-o-calendar-days')
        ->emptyStateActions([
            Tables\Actions\CreateAction::make()
                ->label('Create Event')
                ->icon('heroicon-o-plus'),
        ])
        ->defaultPaginationPageOption(25)
        ->reorderable('sort')
        ->striped()
        ->poll('30s');
}

    public static function getRelations(): array
    {
        return [
            RelationManagers\ParticipantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record:id}'),
            'edit' => Pages\EditPost::route('/{record:id}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * Get the route key for a record.
     * Force Filament to use ID instead of slug.
     * This overrides the model's getRouteKey() method for Filament.
     */
    public static function getRecordRouteKey(Post $record): string
    {
        return (string) $record->getKey();
    }

    /**
     * Override getUrl to always use ID for record routes.
     * This ensures Filament uses ID instead of slug for all URLs.
     */
    public static function getUrl(string $name = 'index', array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        // If this is a record route and we have a record, always use ID
        if (in_array($name, ['view', 'edit']) && isset($parameters['record'])) {
            $record = $parameters['record'];
            if ($record instanceof Post) {
                // Force ID instead of slug
                $parameters['record'] = $record->getKey();
            } elseif (is_string($record) || is_int($record)) {
                // Already an ID, keep it
                $parameters['record'] = $record;
            }
        }
        
        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant);
    }

    /**
     * Resolve the record for Filament routes using ID.
     * The Post model already handles returning ID for admin routes,
     * but we need to ensure Filament resolves by ID.
     */
    public static function resolveRecordRouteBinding($key): ?Post
    {
        // Try ID first (most common case for Filament)
        $record = static::getEloquentQuery()
            ->where('id', $key)
            ->first();
            
        // If not found by ID, it might be a slug (fallback)
        if (!$record && is_numeric($key) === false) {
            $locale = app()->getLocale();
            $record = static::getEloquentQuery()
                ->whereRaw("JSON_EXTRACT(slug, ?) = ?", ["$.{$locale}", $key])
                ->first();
        }
        
        return $record;
    }
}

