<!-- resources/views/dass-prediction/form.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASS-21 Prediction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>DASS-21 Prediction Form</h3>
                    </div>
                    <div class="card-body">
                        <form id="predictionForm">
                            @csrf
                            
                            <h5 class="mt-3">DASS-21 Scores (0-42)</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="depression_score" class="form-label">Depression Score</label>
                                    <input type="number" class="form-control" id="depression_score" 
                                           name="depression_score" min="0" max="42" step="0.1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="anxiety_score" class="form-label">Anxiety Score</label>
                                    <input type="number" class="form-control" id="anxiety_score" 
                                           name="anxiety_score" min="0" max="42" step="0.1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="stress_score" class="form-label">Stress Score</label>
                                    <input type="number" class="form-control" id="stress_score" 
                                           name="stress_score" min="0" max="42" step="0.1" required>
                                </div>
                            </div>
                            
                            <h5 class="mt-3">MSPSS Scores (1-5)</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="so_score" class="form-label">Significant Other</label>
                                    <input type="number" class="form-control" id="so_score" 
                                           name="so_score" min="1" max="5" step="0.1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="family_score" class="form-label">Family</label>
                                    <input type="number" class="form-control" id="family_score" 
                                           name="family_score" min="1" max="5" step="0.1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="friends_score" class="form-label">Friends</label>
                                    <input type="number" class="form-control" id="friends_score" 
                                           name="friends_score" min="1" max="5" step="0.1" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Predict</button>
                        </form>
                        
                        <div id="result" class="mt-4" style="display: none;">
                            <h4>Hasil Prediksi:</h4>
                            <div class="alert" id="resultAlert">
                                <strong id="predictionLabel"></strong>
                                <p>Confidence: <span id="confidence"></span></p>
                                <div id="probabilities"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#predictionForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("predict") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#result').show();
                        
                        // Set alert color based on prediction
                        let alertClass = 'alert-info';
                        if (response.prediction === 'Depression') {
                            alertClass = 'alert-danger';
                        } else if (response.prediction === 'Anxiety') {
                            alertClass = 'alert-warning';
                        } else if (response.prediction === 'Stress') {
                            alertClass = 'alert-warning';
                        } else if (response.prediction === 'Normal') {
                            alertClass = 'alert-success';
                        }
                        
                        $('#resultAlert').removeClass().addClass('alert ' + alertClass);
                        $('#predictionLabel').text('Prediction: ' + response.prediction);
                        $('#confidence').text((response.confidence * 100).toFixed(2) + '%');
                        
                        let probHtml = '<h6>Probabilities:</h6><ul>';
                        for (let [label, prob] of Object.entries(response.probabilities)) {
                            probHtml += `<li>${label}: ${(prob * 100).toFixed(2)}%</li>`;
                        }
                        probHtml += '</ul>';
                        $('#probabilities').html(probHtml);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });
    </script>
</body>
</html>