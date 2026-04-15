<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamPaper;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AspirantController extends Controller
{
    public function dashboard()
    {
        return view('aspirant.dashboard');
    }

    public function subjectsIndex()
    {
        return view('aspirant.subjects.index');
    }

    public function topicsIndex($subjectId)
    {
        $subject = Subject::find($subjectId);

        return view('aspirant.topics.index', [
            'subject' => $subject,
        ]);
    }

    public function practiceIndex()
    {
        return view('aspirant.practice.index');
    }

    public function examsIndex()
    {
        return view('aspirant.exams.index');
    }

    public function questionsIndex($subjectId, $topicId)
    {
        $subject = Subject::find($subjectId);
        $topic = Topic::find($topicId);

        return view('aspirant.questions.index', [
            'subject' => $subject,
            'topic' => $topic,
        ]);
    }

    public function initDashboard(Request $request)
    {
        $user = $this->resolveApiUser($request);

        return response()->json([
            'success' => true,
            'aspirant' => $user,
        ], 200, []);
    }

    public function initSubjects(Request $request)
    {
        $this->resolveApiUser($request);

        return response()->json([
            'subjects' => Subject::get(),
            'success' => true,
        ], 200, []);
    }

    public function initPractice(Request $request)
    {
        $this->resolveApiUser($request);

        return response()->json([
            'subjects' => Subject::get(),
            'success' => true,
        ], 200, []);
    }

    public function randomQuestion(Request $request)
    {
        $this->resolveApiUser($request);

        $subjectId = (int) $request->subject_id;
        $topicIds = $request->topic_ids ?: [];
        $excludeIds = $request->exclude_ids ?: [];

        if (!$subjectId || !is_array($topicIds) || !count($topicIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Subject and topics required',
            ], 200, []);
        }

        $query = Question::where('subject_id', $subjectId)
            ->whereIn('topic_id', $topicIds);

        if (is_array($excludeIds) && count($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        $question = $query->inRandomOrder()->first();

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'No more questions found',
            ], 200, []);
        }

        return response()->json([
            'success' => true,
            'question' => $question,
        ], 200, []);
    }

    public function initTopics(Request $request)
    {
        $this->resolveApiUser($request);

        $subjectId = (int) $request->subject_id;

        return response()->json([
            'topics' => Topic::where('subject_id', $subjectId)->get(),
            'success' => true,
        ], 200, []);
    }

    public function storeTopic(Request $request)
    {
        $user = $this->resolveApiUser($request);

        if (!$user) {
            abort(403, 'Not authorized');
        }

        $cre = [
            'name' => $request->name,
            'subject_id' => $request->subject_id,
        ];

        $rules = [
            'name' => 'required',
            'subject_id' => 'required',
        ];

        $validator = Validator::make($cre, $rules);

        if ($validator->passes()) {
            if ($request->id) {
                $topic = Topic::find($request->id);
                $message = 'Successfully updated';
            } else {
                $topic = new Topic();
                $message = 'Successfully Stored';
            }

            $topic->name = $request->name;
            $topic->subject_id = $request->subject_id;
            $topic->status = $request->status ?? 0;
            $topic->created_at = date('Y-m-d H:i:s');
            $topic->save();

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200, []);
        }

        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 200, []);
    }

    public function initQuestions(Request $request)
    {
        $this->resolveApiUser($request);

        $subjectId = (int) $request->subject_id;
        $topicId = (int) $request->topic_id;
        $questions = Question::where('subject_id', $subjectId)
            ->where('topic_id', $topicId)
            ->get();

        return response()->json([
            'questions' => $questions,
            'success' => true,
        ], 200, []);
    }

    public function storeQuestion(Request $request)
    {
        $user = $this->resolveApiUser($request);

        if (!$user) {
            abort(403, 'Not authorized');
        }

        $cre = [
            'question' => $request->question,
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
        ];

        $rules = [
            'question' => 'required',
            'subject_id' => 'required',
            'topic_id' => 'required',
        ];

        $validator = Validator::make($cre, $rules);

        if ($validator->passes()) {
            if ($request->id) {
                $question = Question::find($request->id);
                $message = 'Successfully updated';
            } else {
                $question = new Question();
                $message = 'Successfully Stored';
            }

            $question->question = $request->question;
            $question->question_hi = $request->question_hi;
            $question->remarks = $request->remarks;
            $question->reference = $request->reference;
            $question->opt_a = $request->opt_a;
            $question->opt_b = $request->opt_b;
            $question->opt_c = $request->opt_c;
            $question->opt_d = $request->opt_d;
            $question->answer = $request->answer;
            $question->negative_marks = $request->negative_marks ?? 0.33;
            $question->paragraph_id = $request->paragraph_id;
            $question->image_file = $request->image_file;
            $question->total_marks = $request->total_marks ?? 1;
            $question->subject_id = $request->subject_id;
            $question->topic_id = $request->topic_id;
            $question->created_at = date('Y-m-d H:i:s');
            $question->save();

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200, []);
        }

        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 200, []);
    }

    public function uploadQuestionImage(Request $request)
    {
        $destination = 'question_images/';

        if ($request->file('media')) {
            $extension = $request->file('media')->getClientOriginalExtension();
            if (in_array($extension, User::fileExtensions())) {
                $file = $request->file('media');
                $nameFile = pathinfo($request->file('media')->getClientOriginalName(), PATHINFO_FILENAME);
                $nameFile = preg_replace('/[^a-zA-Z0-9]/', '', $nameFile);

                $name = 'question' . $nameFile . '_' . strtotime('now') . '.' . strtolower($extension);
                $file = $file->move($destination, $name);
                $data['media'] = $destination . $name;
                $data['media_link'] = url($destination . $name);

                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid file format for image , Valid extentions are  jpg , png ,jpeg',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Please select image',
        ]);
    }

    public function examSubjects(Request $request)
    {
        $this->resolveApiUser($request);

        return response()->json([
            'success' => true,
            'subjects' => Subject::orderBy('name')->get(),
        ], 200, []);
    }

    public function startExam(Request $request)
    {
        $user = $this->resolveApiUser($request);

        $validator = Validator::make($request->all(), [
            'subject_ids' => 'required|array|min:3',
            'subject_ids.*' => 'integer|distinct|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200, []);
        }

        $subjectIds = array_values(array_unique(array_map('intval', $request->subject_ids)));
        $questions = Question::whereIn('subject_id', $subjectIds)
            ->inRandomOrder()
            ->limit(100)
            ->get();

        if ($questions->count() < 100) {
            return response()->json([
                'success' => false,
                'message' => 'At least 100 questions are required across the selected subjects.',
            ], 200, []);
        }

        $examId = 'EXM' . now()->format('YmdHis') . strtoupper(Str::random(6));

        DB::transaction(function () use ($user, $examId, $subjectIds, $questions) {
            Exam::create([
                'user_id' => $user->id,
                'exam_id' => $examId,
                'selected_subject_ids' => $subjectIds,
                'start_time' => now(),
                'duration_minutes' => 60,
                'total_questions' => $questions->count(),
                'status' => 'started',
                'submitted_at' => null,
                'total_score' => 0,
                'attempted' => 0,
                'correct' => 0,
                'wrong' => 0,
                'unattempted' => $questions->count(),
            ]);

            foreach ($questions->values() as $index => $question) {
                ExamPaper::create([
                    'exam_id' => $examId,
                    'question_id' => $question->id,
                    'question_order' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'exam_id' => $examId,
            'start_time' => now()->toDateTimeString(),
            'duration_minutes' => 60,
            'total_questions' => 100,
            'message' => 'Exam started successfully.',
        ], 200, []);
    }

    public function getQuestions(Request $request)
    {
        $user = $this->resolveApiUser($request);
        $exam = $this->findUserExam($user, $request->query('exam_id'));

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.',
            ], 200, []);
        }

        $rows = $this->getExamQuestionRows($exam->exam_id);
        $savedAnswers = UserAnswer::where('user_id', $user->id)
            ->where('exam_id', $exam->exam_id)
            ->pluck('selected_option', 'question_id')
            ->toArray();

        $questions = [];
        foreach ($rows as $row) {
            $questions[] = $this->transformExamQuestion($row, $savedAnswers[$row->id] ?? null);
        }

        if ($exam->status !== 'submitted' && $this->getRemainingSeconds($exam) <= 0) {
            $this->finalizeExam($exam, $user);
            $exam->refresh();
        }

        return response()->json([
            'success' => true,
            'exam' => [
                'exam_id' => $exam->exam_id,
                'status' => $exam->status,
                'start_time' => optional($exam->start_time)->toDateTimeString(),
                'submitted_at' => optional($exam->submitted_at)->toDateTimeString(),
                'duration_minutes' => (int) ($exam->duration_minutes ?: 60),
                'total_questions' => (int) ($exam->total_questions ?: count($questions)),
                'remaining_seconds' => $exam->status === 'submitted' ? 0 : $this->getRemainingSeconds($exam),
            ],
            'questions' => $questions,
            'answer_map' => $savedAnswers,
        ], 200, []);
    }

    public function saveAnswer(Request $request)
    {
        $user = $this->resolveApiUser($request);

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|string',
            'question_id' => 'required|integer|exists:questions,id',
            'selected_option' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200, []);
        }

        $exam = $this->findUserExam($user, $request->exam_id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.',
            ], 200, []);
        }

        if ($exam->status === 'submitted') {
            return response()->json([
                'success' => false,
                'message' => 'Exam already submitted.',
            ], 200, []);
        }

        if ($this->getRemainingSeconds($exam) <= 0) {
            $summary = $this->finalizeExam($exam, $user);

            return response()->json([
                'success' => false,
                'message' => 'Time is over. Exam submitted automatically.',
                'auto_submitted' => true,
                'result' => $summary,
            ], 200, []);
        }

        $belongsToExam = ExamPaper::where('exam_id', $exam->exam_id)
            ->where('question_id', $request->question_id)
            ->exists();

        if (!$belongsToExam) {
            return response()->json([
                'success' => false,
                'message' => 'Question does not belong to this exam.',
            ], 200, []);
        }

        UserAnswer::updateOrCreate(
            [
                'user_id' => $user->id,
                'exam_id' => $exam->exam_id,
                'question_id' => $request->question_id,
            ],
            [
                'selected_option' => $request->selected_option,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Answer saved.',
        ], 200, []);
    }

    public function submitExam(Request $request)
    {
        $user = $this->resolveApiUser($request);

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|string',
            'answers' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200, []);
        }

        $exam = $this->findUserExam($user, $request->exam_id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.',
            ], 200, []);
        }

        $answers = is_array($request->answers) ? $request->answers : [];

        foreach ($answers as $questionId => $selectedOption) {
            $questionId = (int) $questionId;
            if (!$questionId) {
                continue;
            }

            $belongsToExam = ExamPaper::where('exam_id', $exam->exam_id)
                ->where('question_id', $questionId)
                ->exists();

            if (!$belongsToExam) {
                continue;
            }

            UserAnswer::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'exam_id' => $exam->exam_id,
                    'question_id' => $questionId,
                ],
                [
                    'selected_option' => $selectedOption,
                ]
            );
        }

        $summary = $this->finalizeExam($exam, $user);

        return response()->json([
            'success' => true,
            'message' => 'Exam submitted successfully.',
            'result' => $summary,
        ], 200, []);
    }

    public function result(Request $request)
    {
        $user = $this->resolveApiUser($request);
        $exam = $this->findUserExam($user, $request->query('exam_id'));

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.',
            ], 200, []);
        }

        if ($exam->status !== 'submitted' && $this->getRemainingSeconds($exam) <= 0) {
            $this->finalizeExam($exam, $user);
            $exam->refresh();
        }

        $summary = $this->buildResultSummary($exam, $user);

        return response()->json([
            'success' => true,
            'result' => $summary,
        ], 200, []);
    }

    public function answerKey(Request $request)
    {
        $user = $this->resolveApiUser($request);
        $exam = $this->findUserExam($user, $request->query('exam_id'));

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.',
            ], 200, []);
        }

        if ($exam->status !== 'submitted' && $this->getRemainingSeconds($exam) <= 0) {
            $this->finalizeExam($exam, $user);
            $exam->refresh();
        }

        $rows = $this->getExamQuestionRows($exam->exam_id);
        $savedAnswers = UserAnswer::where('user_id', $user->id)
            ->where('exam_id', $exam->exam_id)
            ->pluck('selected_option', 'question_id')
            ->toArray();

        $answerKey = [];

        foreach ($rows as $row) {
            $userAnswer = $savedAnswers[$row->id] ?? null;
            $correctAnswer = $this->normalizeAnswerValue($this->questionField($row, ['correct_answer', 'answer']));

            $status = 'unattempted';
            if ($userAnswer !== null && $userAnswer !== '') {
                $status = $this->normalizeAnswerValue($userAnswer) === $correctAnswer ? 'correct' : 'incorrect';
            }

            $answerKey[] = [
                'question_id' => (int) $row->id,
                'question_no' => (int) $row->question_order,
                'question' => $this->questionField($row, ['question']),
                'options' => [
                    'A' => $this->questionField($row, ['option1', 'opt_a']),
                    'B' => $this->questionField($row, ['option2', 'opt_b']),
                    'C' => $this->questionField($row, ['option3', 'opt_c']),
                    'D' => $this->questionField($row, ['option4', 'opt_d']),
                ],
                'correct_answer' => $correctAnswer,
                'user_answer' => $userAnswer ?: null,
                'status' => $status,
            ];
        }

        return response()->json([
            'success' => true,
            'answer_key' => $answerKey,
        ], 200, []);
    }

    protected function resolveApiUser(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if (!$user || is_string($user)) {
            abort(401, 'Unauthorized');
        }

        return $user;
    }

    protected function findUserExam(User $user, ?string $examId): ?Exam
    {
        if (!$examId) {
            return null;
        }

        return Exam::where('user_id', $user->id)
            ->where('exam_id', $examId)
            ->first();
    }

    protected function getExamQuestionRows(string $examId)
    {
        return DB::table('exam_papers as ep')
            ->join('questions as q', 'q.id', '=', 'ep.question_id')
            ->where('ep.exam_id', $examId)
            ->orderBy('ep.question_order')
            ->select('q.*', 'ep.question_order')
            ->get();
    }

    protected function transformExamQuestion($row, $selectedOption = null): array
    {
        return [
            'id' => (int) $row->id,
            'question_no' => (int) $row->question_order,
            'subject_id' => (int) ($row->subject_id ?? 0),
            'question' => $this->questionField($row, ['question']),
            'option1' => $this->questionField($row, ['option1', 'opt_a']),
            'option2' => $this->questionField($row, ['option2', 'opt_b']),
            'option3' => $this->questionField($row, ['option3', 'opt_c']),
            'option4' => $this->questionField($row, ['option4', 'opt_d']),
            'positive_marks' => (float) $this->questionField($row, ['positive_marks', 'total_marks'], 1),
            'negative_marks' => (float) $this->questionField($row, ['negative_marks'], 0),
            'selected_option' => $selectedOption,
        ];
    }

    protected function questionField($row, array $keys, $default = '')
    {
        foreach ($keys as $key) {
            if (isset($row->{$key}) && $row->{$key} !== null && $row->{$key} !== '') {
                return $row->{$key};
            }
        }

        return $default;
    }

    protected function normalizeAnswerValue($value): string
    {
        return strtoupper(trim((string) $value));
    }

    protected function getRemainingSeconds(Exam $exam): int
    {
        $start = $exam->start_time instanceof Carbon ? $exam->start_time : Carbon::parse($exam->start_time);
        $endTime = $start->copy()->addMinutes((int) ($exam->duration_minutes ?: 60));

        return max(0, now()->diffInSeconds($endTime, false));
    }

    protected function finalizeExam(Exam $exam, User $user): array
    {
        if ($exam->status === 'submitted') {
            return $this->buildResultSummary($exam, $user);
        }

        $summary = $this->buildResultSummary($exam, $user);

        $exam->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'total_score' => $summary['total_score'],
            'attempted' => $summary['attempted'],
            'correct' => $summary['correct'],
            'wrong' => $summary['wrong'],
            'unattempted' => $summary['unattempted'],
        ]);

        return $summary;
    }

    protected function buildResultSummary(Exam $exam, User $user): array
    {
        $rows = $this->getExamQuestionRows($exam->exam_id);
        $savedAnswers = UserAnswer::where('user_id', $user->id)
            ->where('exam_id', $exam->exam_id)
            ->pluck('selected_option', 'question_id')
            ->toArray();

        $attempted = 0;
        $correct = 0;
        $wrong = 0;
        $unattempted = 0;
        $score = 0;

        foreach ($rows as $row) {
            $userAnswer = $savedAnswers[$row->id] ?? null;
            $normalizedUserAnswer = $this->normalizeAnswerValue($userAnswer);
            $correctAnswer = $this->normalizeAnswerValue($this->questionField($row, ['correct_answer', 'answer']));
            $positiveMarks = (float) $this->questionField($row, ['positive_marks', 'total_marks'], 1);
            $negativeMarks = (float) $this->questionField($row, ['negative_marks'], 0);

            if ($normalizedUserAnswer === '') {
                $unattempted++;
                continue;
            }

            $attempted++;

            if ($normalizedUserAnswer === $correctAnswer) {
                $correct++;
                $score += $positiveMarks;
            } else {
                $wrong++;
                $score -= $negativeMarks;
            }
        }

        return [
            'exam_id' => $exam->exam_id,
            'total_questions' => count($rows),
            'attempted' => $attempted,
            'correct' => $correct,
            'wrong' => $wrong,
            'unattempted' => $unattempted,
            'total_score' => round($score, 2),
            'status' => $exam->status === 'submitted' ? 'submitted' : 'in_progress',
        ];
    }
}
