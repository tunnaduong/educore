<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = config('services.gemini.base_url');
    }

    /**
     * Gửi request đến Gemini API
     */
    protected function makeRequest($prompt, $maxTokens = 1000)
    {
        try {
            Log::info('GeminiService: Making API request', [
                'prompt_length' => strlen($prompt),
                'max_tokens' => $maxTokens,
                'api_key_exists' => !empty($this->apiKey),
                'base_url' => $this->baseUrl
            ]);

            $requestData = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => $maxTokens,
                    'temperature' => 0.7,
                    'topP' => 0.8,
                    'topK' => 40
                ]
            ];

            Log::info('GeminiService: Request data prepared', [
                'request_data' => $requestData
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, $requestData);

            Log::info('GeminiService: API response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_length' => strlen($response->body())
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $result = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                Log::info('GeminiService: Successful response parsed', [
                    'has_result' => !empty($result),
                    'result_length' => strlen($result ?? ''),
                    'result_preview' => substr($result ?? '', 0, 200)
                ]);

                return $result;
            }

            Log::error('GeminiService: API error response', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('GeminiService: API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Xử lý JSON response từ AI
     */
    protected function parseJsonResponse($response)
    {
        if (empty($response)) {
            return null;
        }

        // Loại bỏ markdown code block nếu có
        $jsonText = $response;

        // Xử lý trường hợp có ```json ... ```
        if (preg_match('/```json\s*(.*?)\s*```/s', $response, $matches)) {
            $jsonText = $matches[1];
        }
        // Xử lý trường hợp có ``` ... ```
        elseif (preg_match('/```\s*(.*?)\s*```/s', $response, $matches)) {
            $jsonText = $matches[1];
        }

        // Loại bỏ whitespace thừa
        $jsonText = trim($jsonText);

        Log::info('GeminiService: Parsing JSON response', [
            'original_length' => strlen($response),
            'cleaned_length' => strlen($jsonText),
            'cleaned_preview' => substr($jsonText, 0, 200)
        ]);

        try {
            $decoded = json_decode($jsonText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                Log::info('GeminiService: JSON parse successful', [
                    'decoded_type' => gettype($decoded),
                    'is_array' => is_array($decoded)
                ]);
                return $decoded;
            } else {
                Log::error('GeminiService: JSON parse error', [
                    'json_error' => json_last_error_msg(),
                    'cleaned_text' => $jsonText
                ]);
                return null;
            }
        } catch (Exception $e) {
            Log::error('GeminiService: JSON parse exception', [
                'error' => $e->getMessage(),
                'cleaned_text' => $jsonText
            ]);
            return null;
        }
    }

    /**
     * Sửa lỗi ngữ pháp và phát âm
     */
    public function correctGrammarAndPronunciation($text, $language = 'Chinese')
    {
        $prompt = "Hãy sửa lỗi ngữ pháp và phát âm trong đoạn văn sau bằng tiếng {$language}. 
        Trả về kết quả theo format JSON:
        {
            'corrected_text': 'văn bản đã sửa',
            'errors_found': [
                {
                    'original': 'từ gốc',
                    'corrected': 'từ đã sửa',
                    'explanation': 'giải thích lỗi (viết bằng tiếng Việt)'
                }
            ],
            'suggestions': ['gợi ý cải thiện (viết bằng tiếng Việt)']
        }

        Văn bản cần sửa: {$text}";

        $result = $this->makeRequest($prompt, 2000);

        if ($result) {
            $decoded = $this->parseJsonResponse($result);
            if ($decoded) {
                return $decoded;
            }

            // Fallback: trả về text đã sửa nếu không parse được JSON
            return [
                'corrected_text' => $result,
                'errors_found' => [],
                'suggestions' => []
            ];
        }

        return null;
    }

    /**
     * Chấm bài tự luận thông minh
     */
    public function gradeEssay($essay, $criteria, $maxScore = 10)
    {
        $prompt = "Hãy chấm bài tự luận sau dựa trên các tiêu chí đã cho. 
        Trả về kết quả theo format JSON:
        {
            'score': điểm_số,
            'feedback': 'nhận xét chi tiết',
            'criteria_scores': {
                'content': điểm_nội_dung,
                'grammar': điểm_ngữ_pháp,
                'structure': điểm_cấu_trúc,
                'creativity': điểm_sáng_tạo
            },
            'strengths': ['điểm mạnh'],
            'weaknesses': ['điểm yếu'],
            'suggestions': ['gợi ý cải thiện']
        }

        Tiêu chí chấm: {$criteria}
        Điểm tối đa: {$maxScore}
        
        Bài tự luận: {$essay}";

        $result = $this->makeRequest($prompt, 3000);

        if ($result) {
            $decoded = $this->parseJsonResponse($result);
            if ($decoded) {
                return $decoded;
            }

            // Fallback
            return [
                'score' => 0,
                'feedback' => 'Không thể chấm bài tự động',
                'criteria_scores' => [],
                'strengths' => [],
                'weaknesses' => [],
                'suggestions' => []
            ];
        }

        return null;
    }

    /**
     * Kiểm tra và sửa lỗi quiz
     */
    public function validateAndFixQuiz($questions)
    {
        $questionsJson = json_encode($questions, JSON_UNESCAPED_UNICODE);

        $prompt = "Hãy kiểm tra và sửa lỗi trong các câu hỏi quiz sau. 
        Trả về kết quả theo format JSON:
        {
            'fixed_questions': [câu_hỏi_đã_sửa],
            'errors_found': [
                {
                    'question_index': số_thứ_tự,
                    'error_type': 'loại_lỗi',
                    'description': 'mô_tả_lỗi',
                    'fix': 'cách_sửa'
                }
            ],
            'suggestions': ['gợi_ý_cải_thiện (viết bằng tiếng Việt)']
        }

        Các câu hỏi cần kiểm tra: {$questionsJson}";

        $result = $this->makeRequest($prompt, 4000);

        if ($result) {
            $decoded = $this->parseJsonResponse($result);
            if ($decoded) {
                return $decoded;
            }

            // Fallback
            return [
                'fixed_questions' => $questions,
                'errors_found' => [],
                'suggestions' => []
            ];
        }

        return null;
    }

    /**
     * Tạo quiz tự động từ nội dung bài học
     */
    public function generateQuiz($lessonContent, $topic, $difficulty = 'medium', $questionCount = 10)
    {
        $prompt = "Hãy tạo {$questionCount} câu hỏi quiz về chủ đề '{$topic}' dựa trên nội dung bài học sau. 
        Độ khó: {$difficulty}
        
        Trả về kết quả theo format JSON:
        {
            'questions': [
                {
                    'question': 'câu hỏi',
                    'type': 'multiple_choice|fill_blank|essay',
                    'options': ['lựa chọn A', 'lựa chọn B', 'lựa chọn C', 'lựa chọn D'],
                    'correct_answer': 'đáp án đúng',
                    'explanation': 'giải thích',
                    'score': điểm_câu_hỏi
                }
            ],
            'total_score': tổng_điểm,
            'estimated_time': thời_gian_ước_tính_phút
        }

        Nội dung bài học: {$lessonContent}";

        $result = $this->makeRequest($prompt, 5000);

        if ($result) {
            $decoded = $this->parseJsonResponse($result);
            if ($decoded) {
                return $decoded;
            }

            // Fallback
            return [
                'questions' => [],
                'total_score' => 0,
                'estimated_time' => 0
            ];
        }

        return null;
    }

    /**
     * Tạo ngân hàng câu hỏi
     */
    public function generateQuestionBank($topic, $subject, $maxQuestions = 100)
    {
        Log::info('GeminiService: Starting question bank generation', [
            'topic' => $topic,
            'subject' => $subject,
            'maxQuestions' => $maxQuestions,
            'api_key_exists' => !empty($this->apiKey),
            'base_url' => $this->baseUrl
        ]);

        $prompt = "Hãy tạo ngân hàng câu hỏi với tối đa {$maxQuestions} câu hỏi về chủ đề '{$topic}' thuộc môn học '{$subject}'.
        
        Trả về kết quả theo format JSON:
        {
            'questions': [
                {
                    'id': số_thứ_tự,
                    'question': 'câu hỏi',
                    'type': 'multiple_choice|fill_blank|essay|true_false',
                    'difficulty': 'easy|medium|hard',
                    'options': ['lựa chọn A', 'lựa chọn B', 'lựa chọn C', 'lựa chọn D'],
                    'correct_answer': 'đáp án đúng',
                    'explanation': 'giải thích',
                    'tags': ['tag1', 'tag2'],
                    'score': điểm_câu_hỏi
                }
            ],
            'statistics': {
                'total_questions': tổng_số_câu,
                'easy_count': số_câu_dễ,
                'medium_count': số_câu_trung_bình,
                'hard_count': số_câu_khó,
                'multiple_choice_count': số_câu_trắc_nghiệm,
                'fill_blank_count': số_câu_điền_khuyết,
                'essay_count': số_câu_tự_luận
            }
        }";

        $result = $this->makeRequest($prompt, 8000);

        Log::info('GeminiService: API response received', [
            'has_result' => !empty($result),
            'result_length' => strlen($result ?? ''),
            'result_preview' => substr($result ?? '', 0, 200)
        ]);

        if ($result) {
            $decoded = $this->parseJsonResponse($result);

            if ($decoded && !empty($decoded['questions'])) {
                Log::info('GeminiService: Question bank generation successful', [
                    'question_count' => count($decoded['questions']),
                    'has_statistics' => !empty($decoded['statistics'])
                ]);
                return $decoded;
            } else {
                Log::error('GeminiService: Question bank generation failed - invalid structure', [
                    'has_decoded' => !empty($decoded),
                    'has_questions' => !empty($decoded['questions']),
                    'decoded_keys' => $decoded ? array_keys($decoded) : []
                ]);
            }
        }

        Log::error('GeminiService: No result from API');
        return null;
    }

    /**
     * Phân tích và đưa ra gợi ý cải thiện cho bài tập
     */
    public function analyzeAssignment($assignmentContent, $studentSubmission)
    {
        $prompt = "Hãy phân tích bài tập và bài nộp của học sinh để đưa ra gợi ý cải thiện.
        
        Trả về kết quả theo format JSON:
        {
            'analysis': {
                'content_quality': 'đánh giá chất lượng nội dung',
                'completeness': 'đánh giá tính đầy đủ',
                'accuracy': 'đánh giá độ chính xác',
                'creativity': 'đánh giá tính sáng tạo'
            },
            'score_breakdown': {
                'content': điểm_nội_dung,
                'presentation': điểm_trình_bày,
                'originality': điểm_tính_độc_đáo
            },
            'improvement_suggestions': [
                {
                    'category': 'nội_dung|trình_bày|kỹ_thuật',
                    'suggestion': 'gợi ý cụ thể',
                    'priority': 'high|medium|low'
                }
            ],
            'learning_resources': [
                {
                    'type': 'video|document|link',
                    'title': 'tiêu đề tài liệu',
                    'description': 'mô tả',
                    'url': 'đường_dẫn'
                }
            ]
        }

        Nội dung bài tập: {$assignmentContent}
        Bài nộp của học sinh: {$studentSubmission}";

        $result = $this->makeRequest($prompt, 4000);

        if ($result) {
            $decoded = $this->parseJsonResponse($result);
            if ($decoded) {
                return $decoded;
            }

            // Fallback
            return [
                'analysis' => [],
                'score_breakdown' => [],
                'improvement_suggestions' => [],
                'learning_resources' => []
            ];
        }

        return null;
    }
}