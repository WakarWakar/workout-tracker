<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Auth\Access\Response;

class WorkoutPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Workout $workout): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Workouts belonging to another user are treated as not found, so
     * non-owners (including admins) get a 404 instead of a 403.
     */
    public function update(User $user, Workout $workout): bool
    {
        if ($user->id !== $workout->user_id) {
            abort(404);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Workouts belonging to another user are treated as not found, so
     * non-owners (including admins) get a 404 instead of a 403.
     */
    public function delete(User $user, Workout $workout): bool
    {
        if ($user->id !== $workout->user_id) {
            abort(404);
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Workout $workout): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Workout $workout): bool
    {
        return false;
    }
}
