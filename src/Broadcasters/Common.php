<?php

namespace ctf0\Firebase\Broadcasters;

use Illuminate\Broadcasting\Broadcasters\UsePusherChannelConventions;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait Common
{
    use UsePusherChannelConventions;

    /**
     * {@inheritdoc}
     */
    public function auth($request)
    {
        $channelName = $this->normalizeChannelName($request->channel_name);

        if (
            $this->isGuardedChannel($request->channel_name) &&
            !$this->retrieveUser($request, $channelName)
        ) {
            throw new AccessDeniedHttpException();
        }

        return parent::verifyUserCanAccessChannel(
            $request,
            $channelName
        );
    }

    /**
     * {@inheritdoc}
     */
    public function validAuthenticationResponse($request, $result)
    {
        if (is_bool($result)) {
            return json_encode($result);
        }

        $channelName = $this->normalizeChannelName($request->channel_name);

        return json_encode([
            'channel_data' => [
                'user_id'   => $this->retrieveUser($request, $channelName)->getAuthIdentifier(),
                'user_info' => $result,
            ],
        ]);
    }
}
