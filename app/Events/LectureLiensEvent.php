<?php

namespace App\Events;

use DOMNode;
use DOMElement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LectureLiensEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;
    public $path;

    /**
     * Create a new event instance.
     */
    public function __construct($status, $path)
    {
        $this->status=$status;
        $this->path=$path;
    }

    public function extract_text(DOMNode $node) {
        if ($node->nodeType === XML_TEXT_NODE) {
            return $node->textContent;
        } elseif ($node instanceof DOMElement && $node->tagName !== 'script' && $node->tagName !== 'style') {
            $contents = '';
            foreach ($node->childNodes as $child) {
                $text = self::extract_text($child);
                if (!empty($text)) {
                    if (!empty($contents)) {
                        $contents .= ' ';
                    }
                    $contents .= str_replace(':', '>', $text);
                }
            }
            return $contents;
        } else {
            return '';
        }
      }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
