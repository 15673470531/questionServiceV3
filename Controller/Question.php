<?php
namespace Controller;
use Dto\QuestionDto;
use Dto\Request;
use Lib\Msg\Msg;
use Model\QuestionModel;
use Service\AiService;
use Service\QuestionService;
use Utils\Utils;

class Question extends Controller{
    private $questionMd;

    public function __construct() {
        $this->questionMd = new QuestionModel();
    }

    public function commit(Request $request): array {
        $orderId = $request->getParam('order_id',0);
        $content = $request->getParam('content','');
        $userid = $request->getUserId();
        $questionService = new QuestionService();
        return $questionService->questionCommit($userid,$orderId, $content);
    }

    public function commit2(Request $request): array {
        $orderId = $request->getParam('order_id',0);
        $content = $request->getParam('content','');
        $userid = $request->getUserId();
        $questionService = new QuestionService();
        return $questionService->questionCommit2($userid,$orderId, $content);
    }

    /**
     * 历史问题列表
     * @param Request $request
     * @return array
     */
    public function listSummaryQuestions(Request $request): array {
        $opUserId = $request->getUserId();
        $questService = new QuestionService();
        return $questService->listSummaryQuestions($opUserId);
    }

    public function listLatelyQuestions(Request $request): array {
        $userid = $request->getUserId();
        $questService = new QuestionService();
        return $questService->listLatelyQuestions($userid);
    }

    /**
     * 获取详情页面当天的列表
     * @param Request $request
     * @return array
     */
    public function listSameTimeQuestions(Request $request): array {
        $userid = $request->getUserId();
        $questionId = $request->getParam('question_id');
        $questService = new QuestionService();
        return $questService->listSameTimeQuestions($userid, $questionId);
    }

    public function detail($questionId): array {
        $data = $this->questionMd->get($questionId);
        return [true,'success', $data[0] ?? []];
    }

    public function questionDetail(Request $request): array {
        $questionId = $request->getParam('question_id',0);
        $data = $this->questionMd->get($questionId);
        $replyContent = $data[0]['reply_content'];
        $data[0]['reply_content'] = htmlspecialchars_decode($replyContent);
        return [true,'success', $data[0] ?? []];
    }
}
