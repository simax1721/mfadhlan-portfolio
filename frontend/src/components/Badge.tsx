import type { ReactNode } from "react";

export function Badge({ children }: { children: ReactNode }) {
  return (
    <span className="rounded-full border border-border bg-surface px-3 py-1 font-mono text-xs text-text-dim">
      {children}
    </span>
  );
}
