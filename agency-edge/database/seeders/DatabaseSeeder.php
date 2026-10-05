<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = config('agency.admin');

        User::updateOrCreate(
            ['email' => $admin['email']],
            ['name' => $admin['name'], 'password' => $admin['password']],
        );

        // One starter article (drawn from the deck's own point of view) so Insights isn't empty.
        // Edit or delete it from Admin → Insights.
        Post::firstOrCreate(['slug' => 'ai-should-do-a-job'], [
            'title' => 'AI should not be a feature. It should do a job.',
            'category' => 'AI for Marketing',
            'excerpt' => 'Adding AI because it is fashionable changes nothing. Giving it a useful job inside the customer journey changes everything.',
            'body' => <<<'MD'
Everyone has the tools. Few know where to use them.

The advantage is no longer access to digital tools or AI. It is knowing how to connect them to a customer journey and a commercial outcome.

## Four jobs AI can do today

- **Answer** — always-on customer response.
- **Qualify** — identify intent and opportunity.
- **Follow up** — move leads without delay.
- **Learn** — turn conversations into insight.

## What it looks like in practice

A customer asks *"Is this available?"*. An AI assistant answers instantly, the CRM captures the lead, automation triggers the follow-up and the team receives the full context.

That is MarTech when strategy, AI and development are designed as one experience.

**Human strategy. AI leverage. Better customer experience.**
MD,
            'published_at' => now(),
        ]);
    }
}
