import type { Project } from "../lib/types";
import { Badge } from "./Badge";
import { useLocale } from "../i18n/useLocale";
import { temporaryProjectImage } from "../lib/projectImage";

export function FeaturedProject({ project }: { project: Project }) {
  const { t } = useLocale();

  return (
    <article className="reveal mb-12 overflow-hidden rounded-2xl border border-accent/30 bg-surface shadow-sm md:grid md:grid-cols-2">
      <div className="relative aspect-video overflow-hidden border-b border-border md:aspect-auto md:border-b-0 md:border-r">
        <img
          src={project.image_url ?? temporaryProjectImage(project)}
          alt={project.title}
          loading="lazy"
          decoding="async"
          className="h-full w-full object-cover"
        />
      </div>

      <div className="flex flex-col p-6 sm:p-8">
        <p className="font-mono text-xs uppercase tracking-widest text-accent">
          {t("featured.eyebrow")}
        </p>
        <h3 className="mt-2 text-2xl font-bold text-heading">
          {project.title}
        </h3>
        {project.subtitle && (
          <p className="mt-1 text-sm text-accent">{project.subtitle}</p>
        )}

        <div className="mt-5 flex flex-wrap gap-2">
          {project.tech_stack.map((tech) => (
            <Badge key={tech}>{tech}</Badge>
          ))}
        </div>

        <div className="mt-6 space-y-5 text-sm">
          <div>
            <h4 className="mb-1.5 font-mono text-xs font-semibold uppercase tracking-wide text-text-dim">
              {t("featured.challengeLabel")}
            </h4>
            <p className="leading-relaxed text-text">
              {t("featured.challengeText")}
            </p>
          </div>

          {project.bullets.length > 0 && (
            <div>
              <h4 className="mb-1.5 font-mono text-xs font-semibold uppercase tracking-wide text-text-dim">
                {t("featured.builtLabel")}
              </h4>
              <ul className="space-y-1.5 text-text">
                {project.bullets.map((bullet, i) => (
                  <li key={i} className="flex gap-2">
                    <span className="mt-2 h-1 w-1 shrink-0 rounded-full bg-text-dim" />
                    <span>{bullet}</span>
                  </li>
                ))}
              </ul>
            </div>
          )}

          <div>
            <h4 className="mb-1.5 font-mono text-xs font-semibold uppercase tracking-wide text-text-dim">
              {t("featured.resultLabel")}
            </h4>
            <p className="leading-relaxed text-text">
              {t("featured.resultText")}
            </p>
          </div>
        </div>

        <div className="mt-6 flex flex-wrap gap-3">
          {project.demo_url && (
            <a href={project.demo_url} target="_blank" rel="noreferrer" className="btn-primary">
              {t("featured.viewDemo")}
            </a>
          )}
          {project.github_url && (
            <a
              href={project.github_url}
              target="_blank"
              rel="noreferrer"
              className="btn-secondary"
            >
              {t("featured.viewSource")}
            </a>
          )}
        </div>
      </div>
    </article>
  );
}
