<?php

namespace Basilicom\Trello;

class Exporter
{

    /**
     * @var object
     */
    protected $config;

    /**
     * @var string
     */
    protected $viewPath;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * holds cards - key is the card id
     * @var array
     */
    protected $cards = array();

    /**
     * @var array
     */
    protected $actions = array();

    /**
     * @param object $config
     * @param string $viewPath
     * @param string $targetPath
     */
    public function __construct($config, $viewPath, $targetPath)
    {
        $this->config       = $config;
        $this->viewPath     = $viewPath;
        $this->targetPath   = $targetPath;
    }

    /**
     * @param string $fileName
     * @param null|mixed $data
     * @return string
     */
    protected function getTemplate($fileName, $data = null) {

        ob_start();
        include $this->viewPath."/".$fileName;
        return ob_get_clean();
    }

    /**
     * @param $cardA object
     * @param $cardB object
     */
    protected function sortCards($cardA, $cardB)
    {
        return ($cardA->created > $cardB->created);
    }

    /**
     * @param $actionA object
     * @param $actionB object
     */
    protected function sortActions($actionA, $actionB)
    {
        return ($actionA->date < $actionB->date);
    }

    /**
     * Retrieves all actions for this card.
     *
     * @param $card object Trello Card api object
     * @return mixed
     */
    public function getActions($card)
    {
        $url = 'https://api.trello.com/1/cards/'
            . $card->id
            . '/actions'
            . '?key=' . $this->config->key
            . '&token=' . $this->config->token
            .  '&filter=all';

        $dataString = file_get_contents($url);
        $actions = json_decode($dataString);

        return $actions;
    }

    /**
     * @param $boardId string
     */
    protected function fetchBoardData($boardId)
    {

        $this->cards = array();
        $this->actions = array();

        $url = 'https://api.trello.com/1/board/'
            . $boardId
            . '?key=' . $this->config->key
            . '&token=' . $this->config->token
            .  '&cards=all';

        $dataString = file_get_contents($url);
        $board = json_decode($dataString);

        $cnt =0;

        foreach ($board->cards as $card) {

            $cnt++;
            //if ($cnt >5 ) break; // for testing

            $actions = $this->getActions($card);

            $lastModified = 0;
            $cardCreated = 0;

            foreach ($actions as $action) {

                $actionTime = strtotime($action->date);

                if ($action->type == 'createCard') {
                    $cardCreated = $actionTime;
                }

                $lastModified = max($lastModified, $actionTime);

                $this->actions[] = $action;
            }

            $card->actions = $actions;
            $card->created = $cardCreated;
            $card->modified = $lastModified;

            $this->cards[$card->id] = $card;

        }

        usort($this->actions, array($this, 'sortActions'));
        usort($this->cards, array($this, 'sortCards'));

    }

    /**
     *
     */
    protected function generateCards() {

        foreach ($this->cards as $card) {

            $html = $this->getTemplate('card.php', $card);
            file_put_contents($this->targetPath.'/card-'.$card->id.'.html', $html);
        }
    }

    /**
     *
     */
    protected function generateActionIndex() {

        $html = $this->getTemplate('actions.php', $this->actions);
        file_put_contents($this->targetPath.'/actions.html', $html);
    }

    /**
     *
     */
    protected function generateCardIndex() {

        $html = $this->getTemplate('cards.php', $this->cards);
        file_put_contents($this->targetPath.'/cards.html', $html);
    }

    /**
     *
     */
    protected function generateFrameset() {

        $html = $this->getTemplate('frameset.php', $this->cards);
        file_put_contents($this->targetPath.'/index.html', $html);
    }

    /**
     *
     */
    public function export() {

        $this->fetchBoardData($this->config->boardId);

        $this->generateCards();
        $this->generateActionIndex();
        $this->generateCardIndex();
        $this->generateFrameset();

    }

}