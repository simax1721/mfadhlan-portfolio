import { useEffect, useState } from "react";

interface FetchState<T> {
  data: T | null;
  loading: boolean;
  error: string | null;
}

interface InternalState<T> {
  data: T | null;
  error: string | null;
  /** The fetcher whose result `data`/`error` currently reflect. */
  resolvedFor: (() => Promise<T>) | null;
}

/**
 * Runs `fetcher` on mount and again whenever `fetcher` itself changes
 * identity. Wrap the fetcher in `useCallback` with whatever it depends on
 * (e.g. the active locale) so a change there naturally triggers a refetch —
 * no separate, lint-unfriendly dependency array to keep in sync.
 *
 * `loading` is derived by comparing the fetcher a result belongs to against
 * the current one, rather than set synchronously at the top of the effect —
 * that avoids triggering an extra render-on-render pass.
 */
export function useFetch<T>(fetcher: () => Promise<T>): FetchState<T> {
  const [state, setState] = useState<InternalState<T>>({
    data: null,
    error: null,
    resolvedFor: null,
  });

  useEffect(() => {
    let cancelled = false;

    fetcher()
      .then((data) => {
        if (!cancelled) setState({ data, error: null, resolvedFor: fetcher });
      })
      .catch((err: Error) => {
        if (!cancelled)
          setState({ data: null, error: err.message, resolvedFor: fetcher });
      });

    return () => {
      cancelled = true;
    };
  }, [fetcher]);

  return {
    data: state.data,
    error: state.error,
    loading: state.resolvedFor !== fetcher,
  };
}
