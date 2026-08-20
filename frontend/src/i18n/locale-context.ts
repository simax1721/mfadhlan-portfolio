import { createContext } from "react";
import { en } from "./locales/en";
import { id } from "./locales/id";

export type Locale = "en" | "id";

export const DICTIONARIES = { en, id };
export const STORAGE_KEY = "portfolio-locale";

export type Dictionary = typeof en;

export interface LocaleContextValue {
  locale: Locale;
  setLocale: (locale: Locale) => void;
  t: (key: string, vars?: Record<string, string>) => string;
}

export const LocaleContext = createContext<LocaleContextValue | null>(null);

export function getInitialLocale(): Locale {
  const stored = localStorage.getItem(STORAGE_KEY);
  return stored === "en" || stored === "id" ? stored : "en";
}

/** Resolves a dotted path like "hero.greeting" against the dictionary. */
export function lookup(dict: Dictionary, path: string): string {
  const value = path
    .split(".")
    .reduce<unknown>(
      (acc, key) =>
        acc && typeof acc === "object" ? (acc as Record<string, unknown>)[key] : undefined,
      dict,
    );
  return typeof value === "string" ? value : path;
}
