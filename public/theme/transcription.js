function decodeHtmlEntities(str) {
  const txt = document.createElement('textarea');
  txt.innerHTML = str;
  return txt.value;
}
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('transcriptionModal');
  const modalBody = document.getElementById('transcriptionModalBody');
  const modalTitle = document.getElementById('transcriptionModalLabel');

  document.querySelectorAll('.show-transcription-btn').forEach(button => {
      button.addEventListener('click', () => {
          const rawText = button.getAttribute('data-transcription');
          const title = button.getAttribute('data-title');

          const decodedText = decodeHtmlEntities(rawText);

          modalTitle.textContent = title || 'Transcription';
          modalBody.textContent = decodedText || 'No transcription available.';
      });
  });
});