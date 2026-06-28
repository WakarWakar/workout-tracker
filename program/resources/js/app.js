// Add / remove workout set rows on the create and edit workout forms.
// Loaded on every page but no-ops unless the relevant elements are present.
const workoutSetRows = document.getElementById('workout-set-rows');
const workoutSetTemplate = document.getElementById('workout-set-template');
const addWorkoutSetButton = document.getElementById('add-workout-set');

if (workoutSetRows && workoutSetTemplate && addWorkoutSetButton) {
    let nextWorkoutSetIndex = workoutSetRows.querySelectorAll('.workout-set-row').length;

    addWorkoutSetButton.addEventListener('click', () => {
        const templateHtml = workoutSetTemplate.innerHTML.replaceAll('__INDEX__', String(nextWorkoutSetIndex));
        workoutSetRows.insertAdjacentHTML('beforeend', templateHtml);
        nextWorkoutSetIndex += 1;
    });

    workoutSetRows.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-workout-set')) {
            event.target.closest('.workout-set-row')?.remove();
        }
    });
}
