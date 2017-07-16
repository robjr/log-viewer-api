<?php
namespace Controller;

use Service\LogService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Util\File\BackwardReader;
use Util\File\FileValidationException;
use Util\File\FileReader;
use Util\File\FileUtil;

class LogController
{
    public function readLogForward(Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);
            $filePath = FileUtil::sanitizePath(LOG_DIRECTORY, $content['file']);

            $forwardReader = new FileReader($filePath);
            $logService = new LogService($forwardReader);
            $lines = $logService->readBlock($content['block'], 10);

            return new JsonResponse(json_encode($lines), Response::HTTP_OK, [], true);
        } catch (FileValidationException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $exception) {
            return new JsonResponse(
                ['message' => 'Invalid parameters', 'code' => 0],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                ['message' => 'Sorry, an unexpected error occurred :('],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function readLogBackward(Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);
            $filePath = FileUtil::sanitizePath(LOG_DIRECTORY, $content['file']);

            $forwardReader = new BackwardReader($filePath);
            $logService = new LogService($forwardReader);
            $lines = $logService->readBlock($content['block'], 10);

            return new JsonResponse(json_encode($lines), Response::HTTP_OK, [], true);
        } catch (FileValidationException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $exception) {
            return new JsonResponse(
                ['message' => 'Invalid parameters', 'code' => 0],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(
                ['message' => 'Sorry, an unexpected error occurred :('],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
