// routes/web.php atau routes/api.php

use App\Http\Controllers\DASSPredictionController;

Route::get('/predict-form', [DASSPredictionController::class, 'showForm']);
Route::post('/predict', [DASSPredictionController::class, 'predict'])->name('predict');