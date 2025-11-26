<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = [
            'en' => $this->faker->sentence,
            'fr' => $this->faker->sentence,
            'es' => $this->faker->sentence,
        ];


        $content = <<<EOD
        # Full Stack Developer
        Ea enim **dolorem** qui quam velit maiores. [Accusamus](https://https://github.com/abdessamadbettal) maxime ut alias omnis qui at aut. Eligendi est qui quo tenetur atque est ~~placeat~~. Quia expedita cum *voluptatibus* alias ut.
        **Lorem ipsum dolor sit amet, consectetur adipiscing elit**,
        1. sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        2. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
        3. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        * Full Stack Developer with Expertise in PHP and Laravel
        ![](http://localhost/storage/test.jpg)
        Lorem ipsum dolor sit amet,
        consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate ```
        velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
        sunt in culpa qui officia deserunt mollit anim id est laborum```
        > " Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." 
        EOD;

        return [
            'name' => $name,
            'content' => [
                'en' => $content,
                'fr' => $content,
                'es' => $content,
            ],
            'author_id' => \App\Models\User::InRandomOrder()->first()->id,
            'category_id' => \App\Models\Category::InRandomOrder()->first()->id,
            'is_published' => $this->faker->boolean,
            'time_to_read' => $this->faker->numberBetween(1, 60),
        ];;
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            // Random image URLs from Unsplash (free stock photos)
            $imageUrls = [
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1504639725590-34d0984388bd?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=1200&h=800&fit=crop',
            ];

            // Create the temporary directory if it doesn't exist
            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Download and attach random images
            $randomImageUrl = $imageUrls[array_rand($imageUrls)];
            $imageContent = file_get_contents($randomImageUrl);
            $imageName = 'cover_' . uniqid() . '.jpg';
            $tempImagePath = $tempDir . '/' . $imageName;
            File::put($tempImagePath, $imageContent);
            
            $post->addMedia($tempImagePath)
                ->toMediaCollection('image_cover');

            // Download and attach thumbnail
            $randomThumbnailUrl = $imageUrls[array_rand($imageUrls)];
            $thumbnailContent = file_get_contents($randomThumbnailUrl);
            $thumbnailName = 'thumbnail_' . uniqid() . '.jpg';
            $tempThumbnailPath = $tempDir . '/' . $thumbnailName;
            File::put($tempThumbnailPath, $thumbnailContent);
            
            $post->addMedia($tempThumbnailPath)
                ->toMediaCollection('thumbnail');

            // Attach 2 to 4 random tags
            $tags = Tag::inRandomOrder()->take(rand(2, 4))->get();
            $post->tags()->attach($tags);
        });
    }
}
