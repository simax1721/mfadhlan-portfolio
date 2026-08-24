import type { ReactNode } from "react";

/** Shared terminal-window chrome (macOS-style traffic-light dots + label
 * bar) used by the Hero mockup and the full-page loader, so both share the
 * same "developer" visual language. */
export function TerminalWindow({
  label,
  children,
  className = "",
}: {
  label: string;
  children: ReactNode;
  className?: string;
}) {
  return (
    <div
      className={`overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_20px_60px_-20px_rgba(0,0,0,0.6)] ${className}`}
    >
      <div className="flex items-center gap-1.5 border-b border-border bg-surface-2 px-4 py-3">
        <span className="h-2.5 w-2.5 rounded-full bg-[#f87171]/70" />
        <span className="h-2.5 w-2.5 rounded-full bg-[#facc15]/70" />
        <span className="h-2.5 w-2.5 rounded-full bg-[#4ade80]/70" />
        <span className="ml-3 font-mono text-xs text-text-dim">{label}</span>
      </div>
      <div className="p-5 font-mono text-[13px] leading-relaxed">
        {children}
      </div>
    </div>
  );
}
