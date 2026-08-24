/** Resolves with `promise`'s result, but not before `ms` has elapsed —
 * doesn't add extra wait if `promise` was already slower than that.
 *
 * Used to keep the loading screen's terminal typewriter (see
 * `StatusScreens.tsx`) from cutting off mid-line when `/api/bootstrap`
 * resolves faster than the animation takes to play — a real case in
 * production once the response is cache-hit fast, not something that
 * shows up locally against an uncached `php artisan serve`. */
export function withMinDelay<T>(promise: Promise<T>, ms: number): Promise<T> {
  return Promise.all([
    promise,
    new Promise<void>((resolve) => setTimeout(resolve, ms)),
  ]).then(([result]) => result);
}
