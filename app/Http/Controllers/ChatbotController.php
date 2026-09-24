<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private array $knowledgeBase = [
        'summary' => [
            'keywords' => ['summary', 'objective', 'profile', 'about', 'introduction', 'bio'],
            'response' => "✨ **Writing a Great Summary**\n\nYour professional summary should be 3-4 sentences that:\n• State your title and years of experience\n• Highlight your top 2-3 skills\n• Mention a key achievement with numbers\n• State what you're seeking\n\n**Example:**\n*\"Results-driven Software Engineer with 5+ years of experience building scalable web applications. Proficient in React, Node.js, and AWS. Reduced page load times by 40% at XYZ Corp. Seeking a senior role to drive product innovation.\"*"
        ],
        'skills' => [
            'keywords' => ['skill', 'skills', 'tech', 'technology', 'programming', 'language', 'tool', 'framework'],
            'response' => "🛠️ **Listing Skills Effectively**\n\nBest practices for skills:\n• Group them by category (Technical, Soft Skills, Tools)\n• List your strongest skills first\n• Match keywords from the job description\n• Be honest about your proficiency level\n\n**Hot Skills in 2024:**\n• AI/ML: Python, TensorFlow, LangChain\n• Web: React, Next.js, TypeScript\n• Cloud: AWS, Azure, Docker, Kubernetes\n• Soft: Leadership, Communication, Problem-solving"
        ],
        'experience' => [
            'keywords' => ['experience', 'work', 'job', 'employment', 'position', 'company', 'role', 'bullet', 'achievement'],
            'response' => "💼 **Writing Strong Work Experience**\n\nUse the **CAR Formula**:\n• **C**hallenge: What problem did you face?\n• **A**ction: What did YOU do?\n• **R**esult: What was the measurable outcome?\n\n**Example bullets:**\n✅ *\"Developed REST APIs serving 500K daily users, reducing latency by 35%\"*\n✅ *\"Led a team of 6 engineers to deliver a $2M product on time\"*\n❌ Avoid: *\"Responsible for managing team\"*\n\n**Always quantify**: Use %, $, numbers, time saved!"
        ],
        'education' => [
            'keywords' => ['education', 'degree', 'university', 'college', 'gpa', 'study', 'course', 'academic'],
            'response' => "🎓 **Education Section Tips**\n\n• List most recent degree first\n• Include GPA only if 3.5+ (or equivalent)\n• Add relevant coursework if you're a fresh graduate\n• Include honors, dean's list, scholarships\n• Once you have 3+ years experience, education goes after work\n\n**Format:**\n*Bachelor of Computer Science*\n*University Name | City | 2020 – 2024*\n*GPA: 3.8/4.0 | Dean's List 3 semesters*"
        ],
        'format' => [
            'keywords' => ['format', 'layout', 'design', 'template', 'length', 'page', 'font', 'color', 'style', 'ats'],
            'response' => "📄 **Resume Format Best Practices**\n\n• **Length**: 1 page for <5 years; 2 max for senior roles\n• **Font**: Arial, Calibri, or Garamond, size 10-12pt\n• **Margins**: 0.5 to 1 inch\n• **ATS-Friendly**: Avoid tables/graphics in ATS submissions\n• **Sections order**: Summary → Experience → Education → Skills\n\n**Template Choice:**\n• 🏛️ Classic – Best for traditional industries (finance, law)\n• 🎨 Modern – Great for tech, marketing, design\n• ✨ Minimal – Universal, clean, ATS-friendly"
        ],
        'tips' => [
            'keywords' => ['tip', 'tips', 'advice', 'help', 'improve', 'better', 'good', 'best', 'optimize'],
            'response' => "💡 **Top Resume Tips for 2024**\n\n1️⃣ **Tailor every resume** to the job description\n2️⃣ **Use action verbs**: Led, Built, Increased, Reduced, Launched\n3️⃣ **Quantify everything**: Numbers grab attention\n4️⃣ **Keywords matter**: Match terms from the job posting\n5️⃣ **No personal info**: Skip photo, age, marital status (in most countries)\n6️⃣ **Proofread 3 times**: Typos are dealbreakers\n7️⃣ **Save as PDF**: Preserves your formatting\n8️⃣ **Keep it updated**: Review every 6 months"
        ],
        'projects' => [
            'keywords' => ['project', 'projects', 'portfolio', 'github', 'side project', 'personal project'],
            'response' => "🚀 **Showcasing Projects**\n\nProjects prove your skills — especially for junior roles!\n\n**What to include:**\n• Project name and your role\n• Technologies used\n• Problem it solved\n• Live link or GitHub URL\n• Impact/users if any\n\n**Example:**\n*Resume Generator App | Laravel, MySQL, JS*\n*Built a full-stack resume builder with AI assistance, PDF export, and 3 templates. 200+ resumes generated.*"
        ],
        'certifications' => [
            'keywords' => ['certification', 'certificate', 'license', 'credential', 'aws', 'google', 'microsoft', 'course'],
            'response' => "🏆 **Certifications That Matter**\n\nHigh-value certifications by field:\n\n☁️ **Cloud**: AWS Solutions Architect, Google Cloud, Azure\n💻 **Programming**: Meta Front-End, Google IT, Oracle Java\n📊 **Data**: Google Data Analytics, IBM Data Science, Tableau\n🔒 **Security**: CompTIA Security+, CISSP, CEH\n📱 **Mobile**: Flutter, React Native (Udemy/Coursera)\n\nList them with: name, issuer, date, and credential ID."
        ],
        'greeting' => [
            'keywords' => ['hi', 'hello', 'hey', 'start', 'begin', 'help'],
            'response' => "👋 **Hello! I'm your AI Resume Assistant!**\n\nI can help you with:\n• 📝 Writing a professional summary\n• 💼 Crafting impactful experience bullets\n• 🛠️ Listing skills strategically\n• 🎓 Formatting your education\n• 📄 Choosing the right template\n• 🏆 Adding certifications & projects\n• 💡 General resume tips & best practices\n\nWhat would you like help with today?"
        ],
        'ats' => [
            'keywords' => ['ats', 'applicant tracking', 'parsed', 'scan', 'keyword', 'rejection', 'pass'],
            'response' => "🤖 **Beating ATS Systems**\n\nATS (Applicant Tracking Systems) scan resumes before humans see them!\n\n**How to pass ATS:**\n✅ Use standard section headings (Work Experience, Education, Skills)\n✅ Include keywords from the job description\n✅ Avoid headers/footers for critical info\n✅ No images, charts, or graphics\n✅ Use simple bullet points\n✅ Standard fonts only\n✅ Submit as PDF or .docx as specified\n\n**Key tip**: Copy the job description, paste into a word cloud tool, and use those keywords in your resume!"
        ],
    ];

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = strtolower(trim($request->input('message')));

        // Check if OpenAI API key is set, use GPT if available
        $apiKey = config('services.openai.api_key');
        if ($apiKey) {
            return $this->chatWithOpenAI($request->input('message'), $request->input('history', []));
        }

        // Rule-based fallback
        $response = $this->matchKnowledgeBase($message);

        return response()->json([
            'reply'  => $response,
            'source' => 'local',
        ]);
    }

    private function matchKnowledgeBase(string $message): string
    {
        foreach ($this->knowledgeBase as $topic) {
            foreach ($topic['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $topic['response'];
                }
            }
        }

        // Default response for unrecognized queries
        return "🤔 **I'm not sure I understand that.**\n\nHere's what I can help you with:\n• **summary** — Professional summary tips\n• **experience** — Writing strong job bullets\n• **skills** — Listing skills effectively\n• **education** — Education section tips\n• **format** — Resume design & ATS tips\n• **certifications** — What certifications to add\n• **projects** — Showcasing your work\n\nTry asking about any of these topics!";
    }

    private function chatWithOpenAI(string $message, array $history): \Illuminate\Http\JsonResponse
    {
        try {
            $client = new \GuzzleHttp\Client(['timeout' => 30]);

            $messages = [
                [
                    'role'    => 'system',
                    'content' => 'You are a professional resume writing assistant. Help users create outstanding resumes. Provide specific, actionable advice with examples. Use markdown formatting with emojis for clarity. Keep responses concise but comprehensive.',
                ],
            ];

            foreach ($history as $msg) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
            $messages[] = ['role' => 'user', 'content' => $message];

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => 'gpt-3.5-turbo',
                    'messages'    => $messages,
                    'max_tokens'  => 500,
                    'temperature' => 0.7,
                ],
            ]);

            $body  = json_decode($response->getBody(), true);
            $reply = $body['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

            return response()->json(['reply' => $reply, 'source' => 'openai']);
        } catch (\Exception $e) {
            // Fallback to rule-based
            $reply = $this->matchKnowledgeBase(strtolower($message));
            return response()->json(['reply' => $reply, 'source' => 'local']);
        }
    }
}
