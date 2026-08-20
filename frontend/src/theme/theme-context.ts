import { createContext } from "react";

export type Theme = "light" | "dark";

export interface ThemeContextValue {
  theme: Theme;
  toggleTheme: () => void;
}

export const STORAGE_KEY = "portfolio-theme";

export const ThemeContext = createContext<ThemeContextValue | null>(null);

export function getInitialTheme(): Theme {
  const stored = localStorage.getItem(STORAGE_KEY);
  if (stored === "light" || stored === "dark") return stored;

  const prefersLight = window.matchMedia?.(
    "(prefers-color-scheme: light)",
  ).matches;
  return prefersLight ? "light" : "dark";
}
